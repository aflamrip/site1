<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Collection extends Controller {
    public function process() {
        $AuthUser               = $this->getVariable("AuthUser");
        $Route                  = $this->getVariable("Route");
        $Settings               = $this->getVariable("Settings"); 
        $Config['nav']          = 'collections'; 


        // Query 
 
        if(Input::cleaner($Route->params->id)) {  

            $Listing = $this->db->from(
                null,
                '
                SELECT 
                collections.id,
                collections.name,
                collections.color,
                collections.created,
                collections.privacy,
                collections.user_id,
                users.username,
                users.avatar
                FROM `collections`  
                LEFT JOIN users ON collections.user_id = users.id AND collections.user_id IS NOT NULL 
                WHERE collections.id = "' . $Route->params->id . '"'
            )->first();
            // Videos 
            $Collections = $this->db->from(
                null,
                '
                SELECT 
                collections_post.id, 
                posts.id as content_id,
                categories.name,
                categories.self as category_self,
                posts.title,  
                posts.type,  
                posts.self,   
                posts.image
                FROM `collections_post` 
                LEFT JOIN posts ON collections_post.content_id = posts.id     
                LEFT JOIN posts_category ON posts_category.content_id = posts.id  
                LEFT JOIN categories ON categories.id = posts_category.category_id  
                WHERE collections_post.collection_id = "' . $Listing['id'] . '"
                GROUP BY posts.id
                ORDER BY collections_post.sortable ASC'
            )->all();
            $Config['title']        = $Listing['name'].' - '.get($Settings, "data.title", "general");
            $Config['description']  = get($Settings, "data.description", "general");
        } 
 
        $Config['url']          = collection($Listing['id'],$Listing['self']);  
        $this->setVariable("Config",$Config);    
        $this->setVariable('Listing',$Listing); 
        $this->setVariable('Collections',$Collections);   
        if(Input::cleaner($_POST['_ACTION']) == 'save' AND $AuthUser['id'] AND $Listing['user_id']) {
            $this->save();
        }
        $this->view('collection', 'app');
    }

    public function save() { 
        $AuthUser   = $this->getVariable("AuthUser");
        $Listing    = $this->getVariable("Listing");
        if (empty($Notify)) {  
 
            $dataarray          = array(
                "name"         => Input::cleaner($_POST['name']),
                "self"         => Input::seo($_POST['name'])
            );   
            $this->db->update('collections')->where('id',$Listing['id'])->where('user_id',$AuthUser['id'])->set($dataarray); 

            $this->db->delete('collections_post')->in('id',implode(",",$_POST['post']))->done(); 
            $Notify['type']     = 'success';
            $Notify['text']     = __('Changes Saved'); 
            $this->notify($Notify);
            header("location: ".APP.'/collection/'.$Listing['id']);
        }else{ 
            $this->notify($Notify);
        }
        return $this;
    }
}
