<?php echo ads($Ads,3,'mb-3');?>
<div class="detail-header d-flex align-items-center">
    <?php     
        if($Listing['episode_id']) {
            $EpisodeWhere = ' AND posts_video.episode_id = "' . $Listing['episode_id'] . '"';
        }
        $Languages = $this->db->from(
            null,
            '
            SELECT 
            posts_video.id,  
            posts_video.name, 
            posts_video.content_id, 
            posts_video.player, 
            posts_video.sortable, 
            posts_video.embed, 
            s.id as service_id,
            s.name as service_name,
            l.id as language_id,
            l.name as language_name
            FROM `posts_video` 
            LEFT JOIN videos_option AS s ON posts_video.service_id = s.id AND s.type = "service" AND posts_video.service_id IS NOT NULL
            LEFT JOIN videos_option AS l ON posts_video.language_id = l.id AND l.type = "language" AND posts_video.language_id IS NOT NULL
            WHERE posts_video.content_id = "' . $Listing['id'] . '"'.$EpisodeWhere.'
            ORDER BY posts_video.sortable ASC'
        )->all();
    ?>
    <?php if(count($Languages) > 0) { ?>
    <div class="nav-player-select dropdown">
        <?php 
        $i = 1;
        foreach ($Languages as $Language) { 
        ?>
        <?php if($i == 1) { ?>
        <a class="dropdown-toggle btn-service selected" href="#" data-embed="<?php echo $Language['id']?>" <?php if(count($Languages)> 1) { ?> role="button" id="videoSource" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            <?php } ?>>
            <?php echo __('Source :');?>
            <span><?php echo ($Language['name'] ? $Language['name'] : $Language['service_name']);?></span>
        </a>
        <?php } ?>
        <?php if(count($Languages) > 1 AND $i == 1) { ?>
        <div class="dropdown-menu" aria-labelledby="videoSource">
        <?php } ?>
        <?php if(count($Languages) > 1) { ?>
            <?php echo '<button type="button" class="btn-service dropdown-source';if($i == 1) echo ' selected'; echo '" data-embed="'.$Language['id'].'"><span class="name">'. ($Language['name'] ? $Language['name'] : $Language['service_name']).'</span>
            <span class="language">'.$Language['language_name'].'</span></button>';?>
           
        <?php } ?>
        <?php if(count($Languages) > 1 AND count($Languages) == $i) { ?>
        </div>
        <?php } ?>
        <?php $i++; } ?>
    </div>
    <?php } ?>
        <div class="d-flex align-items-center">
            <?php if($AuthUser['id']) { ?>
            <div class="dropdown">
                <button type="button" class="btn-svg save" data-toggle="modal" data-target="#m" data-remote="<?php echo APP.'/modal/collection?id='.$Listing['id'];?>">
                    <svg class="icon" stroke-width="3">
                        <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#bookmark';?>" />
                    </svg>
                    <span><?php echo __('Collection');?></span>
                </button>
            </div>
            <?php } ?>
            <div class="dropdown">
                <button type="button" class="btn-svg share" role="button" id="shareDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg class="icon">
                        <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#share';?>" />
                    </svg>
                    <span><?php echo __('Share');?></span>
                </button>
                <div class="dropdown-menu dropdown-share" aria-labelledby="shareDropdown">
                    <a href="#" class="bg-facebook share-link" data-type="facebook" data-sef="<?php echo post($Listing['id'],$Listing['self'],$Listing['type']);?>">
                        <svg class="icon">
                            <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#facebook';?>" />
                        </svg>
                    </a>
                    <a href="#" class="bg-twitter share-link" data-type="twitter" data-title="<?php echo $Listing['title'];?>" data-sef="<?php echo post($Listing['id'],$Listing['self'],$Listing['type']);?>">
                        <svg class="icon">
                            <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#twitter';?>" />
                        </svg>
                    </a>
                </div>
            </div>
            <button type="button" class="btn-svg report mr-0" data-toggle="modal" data-target="#m" data-remote="<?php echo APP.'/modal/report?id='.$Listing['id'];?>">
                <svg class="icon" stroke-width="3">
                    <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#alert';?>" />
                </svg>
                <span><?php echo __('Report');?></span>
            </button>
        </div>
</div>
<div class="app-detail-embed">
    <div class="embed-col">
        <div class="spinner d-none">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
        <div class="embed-code d-none"></div>
        <div class="embed-play">
            <?php if(count($Languages) > 0) { ?>
            <?php if($Data['politicy'] == 1) { ?>
            <div class="embed-lock">
                <div class="heading"><?php echo __('Removed');?></div>
                <div class="subtext"><?php echo __('Content was removed at the request of the rights holder.');?></div>
            </div>
            <?php } else { ?>
            <?php if($Listing['private'] == '1' AND !$AuthUser['id']) { ?>
            <div class="embed-lock">
                <svg class="icon">
                    <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#alert';?>" />
                </svg>
                <div class="heading"><?php echo __('Members Only');?></div>
                <div class="subtext"><?php echo __('This content is only for members.');?> <a href="<?php echo APP.'/login';?>" class="text-white font-weight-bold"><?php echo __('Login');?></a>, <a href="<?php echo APP.'/register';?>" class="text-white font-weight-bold"><?php echo __('Register');?></a></div>
            </div>
            <?php } else { ?>
            <div class="embed-cover lazy" data-src="<?php echo UPLOAD.'/cover/large-cover-'.$Listing['image'];?>"></div>
            <?php echo ads($Ads,6,'embed-video-ads');?>
            <div class="play-btn" data-id="<?php echo $Selected['id'];?>">
                <svg class="icon">
                    <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#play';?>" />
                </svg>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } else { ?>
            <div class="embed-lock">
                <div class="heading"><?php echo __('Not yet available !');?></div>
                <div class="subtext"><?php echo __('Content not yet trackable');?></div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php echo ads($Ads,2,'embed-ads');?>
</div>