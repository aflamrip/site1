<div class="app-section">
    <div class="app-heading">
        <div class="text">
            <?php echo $HomeModule['name'];?>
        </div>
        <a href="<?php echo APP.'/series';?>" class="all">
            <?php echo __('All');?></a>
    </div>
    <div class="row row-cols-5 list-scrollable">
        <?php  
        if(!$ModuleData['sorting']) {
            $OrderBy = 'id DESC';
        }else{
            $OrderBy = $ModuleData['sorting'];
        }
        $Newests = $this->db->from(null,'
            SELECT 
            posts.id, 
            posts.title, 
            posts.title_sub, 
            posts.self, 
            posts.type, 
            posts.image, 
            posts.create_year,
            posts.quality,
            posts.imdb,
            posts.created,
            categories.name,
            categories.self as category_self
            FROM `posts` 
            LEFT JOIN posts_category ON posts_category.content_id = posts.id  
            LEFT JOIN categories ON categories.id = posts_category.category_id  
            WHERE posts.type = "serie" AND posts.status = "1"
            GROUP BY posts_category.content_id
            ORDER BY posts.'.$ModuleData['sorting'].'
            LIMIT 0,'.$HomeModule['data_limit'])
            ->all();
        foreach ($Newests as $Newest) {
        ?>
        <div class="col">
            <div class="list-movie">
                <a href="<?php echo post($Newest['id'],$Newest['self'],$Newest['type']);?>" class="list-media">
                    <?php if($Newest['quality'] || $Newest['imdb']) { ?>
                    <div class="list-media-attr">
                        <?php if($Newest['quality']) { ?>
                        <div class="quality">
                            <?php echo $Newest['quality'];?>
                        </div>
                        <?php } ?>
                        <?php if($Newest['imdb']) { ?>
                        <div class="imdb">
                            <span>
                                <?php echo $Newest['imdb'];?></span>
                            <svg x="0px" y="0px" width="36px" height="36px" viewBox="0 0 36 36">
                                <circle fill="none" stroke-width="1" cx="18" cy="18" r="16" stroke-dasharray="<?php echo round($Newest['imdb'] / 10 * 100);?> 100" stroke-dashoffset="0" transform="rotate(-90 18 18)"></circle>
                            </svg>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="play-btn">
                        <svg class="icon">
                            <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#play';?>" />
                        </svg>
                    </div>
                    <div class="media media-cover" data-src="<?php echo UPLOAD.'/cover/'.$Newest['image'];?>"></div>
                </a>
                <div class="list-caption">
                    <?php if(get($Settings,'data.titlesub','general') == 1) { ?>
                    <a href="<?php echo post($Newest['id'],$Newest['self'],$Newest['type']);?>" class="list-titlesub">
                        <?php echo $Newest['title_sub'];?>
                    </a>
                    <?php } ?>
                    <a href="<?php echo post($Newest['id'],$Newest['self'],$Newest['type']);?>" class="list-title">
                        <?php echo $Newest['title'];?></a> 
                    <a href="<?php echo post($Newest['id'],$Newest['self'],$Newest['type']);?>" class="list-category">
                        <?php echo $Newest['name'];?></a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>