<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tmdb extends Controller {
	public function process() {
		$AuthUser 	= $this->getVariable("AuthUser");
		$Route 		= $this->getVariable("Route");
		$Settings 	= $this->getVariable("Settings"); 

        $Config['nav']                  = 'tools'; 

        if($_POST['_ACTION']) {
            foreach ($_POST as $key => $value) {
                if($value) {
                    $Filter[$key] = $value;
                }
            }
            if(count($Filter) > 1) {
                header("location: ".APP.'/admin/tmdb?filter='.json_encode($Filter));
            } else {
                header("location: ".APP.'/admin/tmdb');
            }
        }

        $Filter     = json_decode($_GET['filter'], true); 
        $this->setVariable("Filter",$Filter);   
        $this->setVariable("Config",$Config);   
        if($Filter['type']) {
            $this->listings();
        } elseif(Input::cleaner($_GET['_ACTION']) == 'insert') {
            $this->insert(); 
        }
		$this->view('tmdb', 'admin');
	}

    public function listings() {
        $Filter     = $this->getVariable("Filter");
        $Settings   = $this->getVariable("Settings");

        $this->page             = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        if ($this->page) $ApiFilter .= '&page=' . $this->page;
  
        $Client     = new \GuzzleHttp\Client();
        if(($Filter['type'] == 'movie' || $Filter['type'] == 'tv') AND $Filter['q']) {
        $Response   = $Client->request('GET', 'https://api.themoviedb.org/3/search/' . Input::cleaner($Filter['type']) . '?query=' . Input::cleaner($Filter['q']) . $ApiFilter . '&api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api'));
        } else {

        $Response   = $Client->request('GET', 'https://api.themoviedb.org/3/discover/'.Input::cleaner($Filter['type']).'?sort_by='.Input::cleaner($Filter['sort']).$ApiFilter . '&api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api'));
        }
        $Results = json_decode($Response->getBody() , true);

        foreach ($Results['results'] as $Result) {
            if (Input::cleaner($Filter['type']) == 'movie') {
     
                $Listings[] = [
                    'id'        => trim($Result['id']),
                    'type'      => 'movie',
                    'link'      => 'https://tmdb.org/movie/' . $Result['id'],
                    'title'     => $Result['original_title'],
                    'image'     => 'https://image.tmdb.org/t/p/w220_and_h330_face/' . $Result['poster_path']
                ];
            } elseif (Input::cleaner($Filter['type']) == 'tv') {

                $Listings[] = [
                    'id'        => trim($Result['id']),
                    'type'      => 'tv',
                    'link'      => 'https://tmdb.org/tv/' . $Result['id'],
                    'title'     => $Result['original_name'],
                    'image'     => 'https://image.tmdb.org/t/p/w220_and_h330_face/' . $Result['poster_path']
                ];
            }
        }

        $this->paginationLimit  = 20;
        $this->totalRecord      = $Results['total_results'];
        $this->pageCount        = ceil($this->totalRecord / $this->paginationLimit);

        $Pagination         = $this->showPagination(APP.'/admin/tmdb?filter='.json_encode($Filter).'&page=[page]');
        $this->setVariable("Listings",$Listings);   
        $this->setVariable("Pagination",$Pagination);

    }

    public function insert() {
  
        $Settings   = $this->getVariable("Settings");
        $FilterId       = Input::cleaner($_GET['id']);
        $FilterType     = Input::cleaner($_GET['type']);
        if ($FilterId) {

            // Guzzle Get
            $Client     = new \GuzzleHttp\Client();
            $Response   = $Client->request(
                'GET', 
                'https://api.themoviedb.org/3/'.$FilterType.'/' . $FilterId . '?api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api')
            );
            $Listing    = json_decode($Response->getBody() , true);
            
            // Listing Check
            if($Listing['id']) {

                // Check Type
                if($FilterType == 'movie') {
                    $Title      = $Listing['original_title'];
                    $TitleTr    = $Listing['title']; 
                    $PostType   = 'movie'; 
                } elseif($FilterType == 'tv') {
                    $Title      = $Listing['original_name'];
                    $TitleTr    = $Listing['name']; 
                    $PostType   = 'serie'; 
                }

                // Check Title and Image
                if ($Title and $Listing['poster_path']) {

                    // Get Trailer
                    $Videos     = $Client->request(
                        'GET', 
                        'https://api.themoviedb.org/3/'.$FilterType.'/' . $FilterId . '/videos?api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api')
                    );
                    $Video      = json_decode($Videos->getBody() , true);
                    if ($Video['results'][0]['site'] == 'YouTube') {
                        $Trailer = 'https://www.youtube.com/embed/' . $Video['results'][0]['key'];
                    } 

                    // Image Upload
                    if($Listing['poster_path']) {
                        $Path = UPLOADPATH . '/tmp/' . Input::seo($Title) . '.jpg';
                        downloader('https://image.tmdb.org/t/p/original/'.$Listing['poster_path'], $Path);
                        $_FILES['image'] = $Path;
                        $foo = new \Verot\Upload\Upload($_FILES['image']);
                        if ($foo->uploaded) {
                            $foo->allowed = array('image/*');
                            $foo->file_auto_rename = true;
                            $foo->file_new_name_body = Input::seo($Title);
                            $foo->image_resize = true;
                            $foo->image_ratio_crop = true;
                            $foo->image_x = MOVIE_X;
                            $foo->image_y = MOVIE_Y;
                            $foo->image_convert = 'webp';
                            $foo->jpeg_quality = 100;
                            $foo->Process(UPLOADPATH . '/cover/');
                            if ($foo->processed) {
                                $Image = $foo->file_dst_name;
                                $thumb = new \Verot\Upload\Upload($_FILES['image']);
                                $thumb->allowed = array('image/*');
                                $thumb->file_auto_rename = true;
                                $thumb->file_new_name_body = 'thumb-' . Input::seo($Title);
                                $thumb->image_resize = true;
                                $thumb->image_ratio_crop = true;
                                $thumb->image_x = THUMB_MOVIE_X;
                                $thumb->image_y = THUMB_MOVIE_Y;
                                $thumb->image_convert   = 'webp';
                                $thumb->jpeg_quality = 100;
                                $thumb->Process(UPLOADPATH . '/cover/');
                                unlink($Path);
                            }
                        }
                    }

                    if($Listing['poster_path']) {
                        $Path = UPLOADPATH . '/tmp/' . Input::seo($Title) . '.jpg';
                        downloader('https://image.tmdb.org/t/p/w1920_and_h1080_multi_faces/'.$Listing['poster_path'], $Path);
                        $_FILES['cover'] = $Path;
                        $foo = new \Verot\Upload\Upload($_FILES['cover']);
                        if ($foo->uploaded) {
                            $foo->allowed = array('image/*');
                            $foo->file_auto_rename = true;
                            $foo->file_new_name_body = 'cover-'.Input::seo($Title);
                            $foo->image_resize = true;
                            $foo->image_ratio_crop = true;
                            $foo->image_x = COVER_X;
                            $foo->image_y = COVER_Y;
                            $foo->image_convert = 'webp';
                            $foo->jpeg_quality = 100;
                            $foo->Process(UPLOADPATH . '/cover/');
                            if ($foo->processed) {
                                $Cover = $foo->file_dst_name;
                                $thumb = new \Verot\Upload\Upload($_FILES['cover']);
                                $thumb->allowed = array('image/*');
                                $thumb->file_auto_rename = true;
                                $thumb->file_new_name_body = 'thumb-cover-' . Input::seo($Title);
                                $thumb->image_resize = true;
                                $thumb->image_ratio_crop = true;
                                $thumb->image_x = THUMB_COVER_X;
                                $thumb->image_y = THUMB_COVER_Y;
                                $thumb->image_convert   = 'webp';
                                $thumb->jpeg_quality = 100;
                                $thumb->Process(UPLOADPATH . '/cover/');

                                $big = new \Verot\Upload\Upload($_FILES['cover']);
                                $big->allowed = array('image/*');
                                $big->file_auto_rename = true;
                                $big->file_new_name_body = 'large-cover-' . Input::seo($Title);
                                $big->image_resize = true;
                                $big->image_ratio_crop = true;
                                $big->image_x = LARGE_COVER_X;
                                $big->image_y = LARGE_COVER_Y;
                                $big->image_convert = 'webp';
                                $big->jpeg_quality = 100;
                                $big->Process(UPLOADPATH . '/cover/');
                                
                                unlink($Path);
                            }
                        }
                    }
                    // Country
                    $Country        = $this->db->from('countries')->where('language',mb_strtoupper($Listing['original_language'],"UTF-8"))->first();
                    $CreateYear     = ($PostType == 'movie' ? explode('-',$Listing['release_date']) : explode('-',$Listing['first_air_date']));
                    // Query Data
                    $Data = array(
                        'type'          => $PostType,
                        'title'         => Input::cleaner($Title),
                        'title_sub'     => Input::cleaner($TitleTr),
                        'self'          => Input::seo($Title),
                        'image'         => $Image,
                        'cover'         => $Cover,
                        'description'   => Input::cleaner($Listing['overview']),
                        'country'       => Input::cleaner($Country['id']),
                        'imdb'          => Input::cleaner($Listing['vote_average']),
                        'quality'       => Input::cleaner($Listing['quality']),
                        'create_year'   => $CreateYear[0],
                        'duration'      => ($PostType == 'movie' ? $Listing['runtime'] : $Listing['episode_run_time'][0]),
                        'trailer'       => Input::cleaner($Trailer),
                        'data'          => json_encode($Settings['data'], JSON_UNESCAPED_UNICODE),
                        'imdb_id'       => Input::cleaner($Listing['id']),
                        'status'        => (int)Input::cleaner(2),
                        'created'       => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('posts')->set($Data);

                    // Post ID
                    $LastId = $this->db->lastId();

                    // Categories
                    foreach ($Listing['genres'] as $Genre) {

                        $Category       = $this->db->from('categories')->where('name',$Genre['name'])->first();
                        if($Category['id']) {
                            $dataarray  = array(
                                "category_id"   => Input::cleaner($Category['id']),
                                "content_id"    => $LastId
                            );
                            $this->db->insert('posts_category')->set($dataarray);
                        }
                    }
                    // Seasons

                    if($_GET['season'] == 'add' AND $PostType == 'serie') { 
                        $iSeason = 1;
                        foreach ($Listing['seasons'] as $Season) {
                            $SeasonNumber = Input::cleaner(filter_var(trim($Season['season_number']), FILTER_SANITIZE_NUMBER_INT))+1;
                            $dataarray = array(
                                "content_id"        => $LastId,
                                "name"              => $iSeason
                            );
                            $this->db->insert('posts_season')->set($dataarray);
                            $iSeason++;
                            $SeasonId = $this->db->lastId();
                            if($_GET['episode'] == 'add') { 
                                $Episodes   = $Client->request(
                                    'GET', 
                                    'https://api.themoviedb.org/3/tv/' . $FilterId . '/season/'.$Season['season_number'].'?api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api')
                                );
                                $Episodes   = json_decode($Episodes->getBody() , true);
                                foreach ($Episodes['episodes'] as $Episode) {
                                
                                    $Path = UPLOADPATH . '/tmp/' . Input::seo($Title.'-'.$SeasonNumber.'-'.$Episode['episode_number']) . '.jpg';
                                    downloader('https://image.tmdb.org/t/p/w227_and_h127_bestv2/'.$Episode['still_path'], $Path);
                                    $foo = new \Verot\Upload\Upload($Path);
                                    if ($foo->uploaded) {
                                        $foo->allowed = array('image/*');
                                        $foo->file_auto_rename = true;
                                        $foo->file_new_name_body = Input::seo($Title.'-'.$SeasonNumber.'-'.$Episode['episode_number']);
                                        $foo->image_resize = true;
                                        $foo->image_ratio_crop = true;
                                        $foo->image_x = EPISODE_X;
                                        $foo->image_y = EPISODE_Y;
                                        $foo->jpeg_quality = 100;
                                        $foo->image_convert = 'webp';
                                        $foo->Process(UPLOADPATH . '/episode/');
                                        if ($foo->processed) {
                                            $Image = $foo->file_dst_name;
                                        }
                                    }
                                    unlink($Path);
                                    $Data = array(
                                        'name'          => Input::cleaner($Episode['episode_number']),
                                        'self'          => Input::seo($Episode['episode_number']), 
                                        'description'   => Input::cleaner($Episode['name']),
                                        'season_id'     => $SeasonId,
                                        'content_id'    => $LastId,
                                        'image'         => $Image,
                                        'status'        => 1,
                                        'created'       => date('Y-m-d H:i:s')
                                    );
                                    $this->db->insert('posts_episode')->set($Data);
                                    
                                    $EpisodeId = $this->db->lastId();
                                }
                            }
                        }
                    }
                    
                    if($_GET['actor'] == 'add') { 
                        // Actors
                        $Credits    = $Client->request(
                            'GET', 
                            'https://api.themoviedb.org/3/'.$FilterType.'/' . $FilterId . '/credits?api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api')
                        );
                        $Credits    = json_decode($Credits->getBody() , true);

                        foreach ($Credits['cast'] as $Credit) { 
                            if($Credit['name'] AND $Credit['profile_path']) {
                                
                                // Check Database
                                $CheckActor = $this->db->from('actors')
                                ->where('self', Input::seo($Credit['name']))
                                ->or_where('api_id', $Credit['id'])
                                ->first();

                                if (!$CheckActor['id'] AND $Credit['name']) {

                                    $ActorResponse  = $Client->request(
                                        'GET', 
                                        'https://api.themoviedb.org/3/person/' . $Credit['id'] . '?api_key=' . get($Settings,'data.tmdb_api','api') . '&language='.get($Settings,'data.tmdb_language','api')
                                    );
                                    $Actor          = json_decode($ActorResponse->getBody() , true);
                                    $SettingsData['data']['place_of_birth'] = $Actor['place_of_birth'];
                                    $SettingsData['data']['deathday']       = $Actor['deathday'];

                                    $Path = UPLOADPATH . '/tmp/' . Input::seo($Actor['name']) . '.jpg';
                                    downloader('https://image.tmdb.org/t/p/w235_and_h235_face/'.$Actor['profile_path'], $Path);
                                    $foo = new \Verot\Upload\Upload($Path);
                                    if ($foo->uploaded) {
                                        $foo->allowed = array('image/*');
                                        $foo->file_auto_rename = true;
                                        $foo->file_new_name_body = Input::seo($Actor['name']);
                                        $foo->image_resize = true;
                                        $foo->image_ratio_crop = true;
                                        $foo->image_x = ACTOR_X;
                                        $foo->image_y = ACTOR_Y;
                                        $foo->image_convert = 'webp';
                                        $foo->jpeg_quality = 100;
                                        $foo->Process(UPLOADPATH . '/actor/');
                                        if ($foo->processed) {
                                            $Image = $foo->file_dst_name;
                                            $thumb = new \Verot\Upload\Upload($Path);
                                            $thumb->allowed = array('image/*');
                                            $thumb->file_auto_rename = true;
                                            $thumb->file_new_name_body = 'thumb-' . Input::seo($Actor['name']);
                                            $thumb->image_resize = true;
                                            $thumb->image_ratio_crop = true;
                                            $thumb->image_x = THUMB_ACTOR_X;
                                            $thumb->image_y = THUMB_ACTOR_Y;
                                            $thumb->image_convert   = 'webp';
                                            $thumb->jpeg_quality = 100;
                                            $thumb->Process(UPLOADPATH . '/actor/');
                                        }
                                        unlink($Path);
                                    }
                                    $Data = array(
                                        'name'      => Input::cleaner($Actor['name']),
                                        'self'      => Input::seo($Actor['name']),
                                        'image'     => $Image,
                                        'biography' => Input::cleaner($Actor['biography']),
                                        'gender'    => Input::cleaner($Actor['gender']),
                                        'data'      => json_encode($SettingsData['data'], JSON_UNESCAPED_UNICODE),
                                        'api_id'    => Input::cleaner($Actor['id']),
                                        'imdb_id'   => Input::cleaner($Actor['imdb_id'])
                                    );
                                    $this->db->insert('actors')->set($Data);
                                    $Actor_id = $this->db->lastId();
                                    $Image      = null;
                                    $SettingsData   = null;
                                    $Path       = null;
                                    $_FILES['image']        = null;
                                } else {
                                    $Actor_id = $CheckActor['id'];
                                }
                                $dataarray = array(
                                    "actor_id"          => $Actor_id,
                                    "character_name"    => Input::cleaner($Credit['character']),
                                    "content_id"        => $LastId,
                                    "sortable"          => Input::cleaner($Actor['sortable'])
                                );
                                $this->db->insert('posts_actor')->set($dataarray);
                            } 

                        }
                    }
                }
            }

            echo json_encode(array(
                "status"    => 'success',
                "text"      => __('Data added successfully')
            ));
        } else {
            echo json_encode(array(
                "status"    => 'danger',
                "text"      => __('Error')
            ));

        }

        die();
    }
    public function showPagination($url=null, $class = 'active',$small=null) {
        if ($this->totalRecord > PAGE_LIMIT) {
            if($small) {
                $this->html .= '<ul class="pagination mb-3">';
            }else{
                $this->html .= '<ul class="pagination mb-3">';
            }
            for ($i = $this->page - 2; $i < $this->page + 2 + 1; $i ++) {
                if ($i > 0 && $i <= $this->pageCount) {
                    $this->html .= '<li class="page-item ';
                    $this->html .= ($i == $this->page ? 'active' : null);
                    $this->html .= '"><a class="page-link border-0" href=\'' . str_replace('[page]', $i, $url) . '\' rel="nofollow">' . $i . '</a>';
                }
            }
            $this->html .= '</ul>';
            return $this->html;
        }
    }
}