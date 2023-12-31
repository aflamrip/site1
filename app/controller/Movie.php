<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Movie extends Controller {
	public function process() {
		$AuthUser 				= $this->getVariable("AuthUser");
		$Route 					= $this->getVariable("Route");
		$Settings 				= $this->getVariable("Settings"); 
        $Config['nav']          = 'movies'; 
		if ($Route->params->id) {
	        $Listing = $this->db->from(null,'
	            SELECT 
	            posts.id, 
	            posts.title, 
	            posts.title_sub, 
                posts.description, 
                posts.self, 
	            posts.image, 
                posts.hit, 
                posts.comment, 
                posts.private, 
                posts.imdb, 
                posts.type, 
                posts.trailer,
                posts.duration,
	            posts.status,
                posts.create_year,
	            posts.data,
	            posts.created,
                countries.name as country_name,
                (SELECT 
                COUNT(reactions.content_id) 
                FROM reactions 
                WHERE reactions.reaction = "up" AND content_id = posts.id) AS likes, 
                (SELECT 
                COUNT(reactions.content_id) 
                FROM reactions 
                WHERE reactions.reaction = "down" AND content_id = posts.id) AS dislikes
	            FROM `posts` 
	            LEFT JOIN posts_category ON posts_category.content_id = posts.id  
	            LEFT JOIN categories ON categories.id = posts_category.category_id  
                LEFT JOIN countries ON countries.id = posts.country  
	            WHERE posts.id = "'. $Route->params->id .'" AND posts.status = "1"
	            GROUP BY posts.id
	            '.$OrderBy)
	            ->first(); 
            $Data       = json_decode($Listing['data'], true);
            if($AuthUser['id']) {
                $Vote = $this->db->from('reactions')->where('user_id',$AuthUser['id'])->where('content_id',$Listing['id'])->first();
            }
            // Actors
            $Actors = $this->db->from(
                null,
                '
                SELECT 
                posts_actor.id, 
                posts_actor.character_name, 
                posts_actor.sortable, 
                a.id as actor_id,
                a.name,  
                a.self,  
                a.api_id,  
                a.image
                FROM `posts_actor` 
                LEFT JOIN actors AS a ON posts_actor.actor_id = a.id     
                WHERE posts_actor.content_id = "' . $Listing['id'] . '"
                ORDER BY posts_actor.sortable ASC'
            )->all(); 


            // Categories 
            $Categories = $this->db->from(
                null,
                '
                SELECT 
                categories.id, 
                categories.name, 
                categories.self
                FROM `posts_category` 
                LEFT JOIN categories ON posts_category.category_id = categories.id     
                WHERE posts_category.content_id = "' . $Listing['id'] . '"
                ORDER BY posts_category.id ASC'
            )->all(); 
            foreach ($Categories as $Category) {
                $SimilarsCategory .= '"'.$Category['id'].'",';
            } 

            // Similars
            $Similars = $this->db->from(null,'
                SELECT 
                posts.id, 
                posts.title, 
                posts.title_sub, 
                posts.quality, 
                posts.image, 
                posts.self, 
                posts.type, 
                posts.create_year,
                posts.status,
                posts.created
                FROM `posts` 
                LEFT JOIN posts_category ON posts_category.content_id = posts.id  
                LEFT JOIN categories ON categories.id = posts_category.category_id  
                WHERE posts.status = "1" AND posts_category.category_id IN ('.rtrim($SimilarsCategory,',').') AND posts.id NOT IN ('.$Listing['id'].') AND posts.type = "movie"
                GROUP BY posts.id
                '.$OrderBy.'
                LIMIT 0,6')
                ->all();

            if($AuthUser['id']) {
                $SelectCollection = $this->db->from('collections_post')->where('content_id',Input::cleaner($Listing['id']))->where('user_id',Input::cleaner($AuthUser['id']))->first();
          
                // Collections 
                $Collections = $this->db->from(
                    null,
                    '
                    SELECT 
                    collections.id, 
                    collections.name, 
                    collections.self
                    FROM `collections` 
                    WHERE collections.user_id = "' . $AuthUser['id'] . '"
                    ORDER BY collections.id ASC
                    LIMIT 0,10'
                )->all(); 
            }
            if($Route->params->video) {
                $RouteVideo = ($Route->params->video-1);
            } else {
                $RouteVideo = 0;
            }  

            $Likes          = $Listing['likes'];
            $Dislikes       = $Listing['dislikes'];
            $TotalReaction  = $Likes + $Dislikes;
            $Likes          = round($Likes / $TotalReaction * 100);
        }
        require PATH . '/config/array.config.php'; 
	 
		
		$Config['title'] 		= str_replace('${title}', $Listing['title'], get($Settings, "data.movie_title", "seo"));
		$Config['description'] 	= str_replace('${title}', $Listing['title'], get($Settings, "data.movie_description", "seo"));
        $Config['type']         = 'post'; 
        $Config['id']           = $Listing['id']; 

        $Config['ogtype']       = 'video.movie';  
        $Config['share']        = true;  
        $Config['image']        = UPLOAD.'/cover/'.$Listing['image'];  
        $Config['url']          = post($Listing['id'],'movie');   

        $Config['player']       = true;  
        $Config['comments']     = true;  
        
		$this->setVariable("Config", $Config);
        $this->setVariable('Listing', $Listing);
        $this->setVariable('Data', $Data);
        $this->setVariable('Categories', $Categories);
        $this->setVariable('Actors', $Actors);   
        $this->setVariable('Likes', $Likes); 
        $this->setVariable('Similars', $Similars); 
        $this->setVariable('Vote', $Vote); 
		$this->view('movie', 'app');
	}
}
