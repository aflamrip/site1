<?php require PATH . '/theme/view/common/header.php';?>
<div class="flex-fill">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo APP.'/actors';?>"><?php echo __('Actors');?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $Listing['name'];?></li>
        </ol>
    </nav>
    <?php echo ads($Ads,3,'mb-3');?>
    <div class="app-section">
        <div class="actor-profile">
            <div class="row">
                <div class="col-md-3">
                    <div class="media" data-src="<?php echo UPLOAD.'/actor/'.$Listing['image'];?>"></div>
                    <?php if($Listing['gender']) { ?>
                    <div class="profile-attr-small">
                        <div class="attr"><?php echo __('Gender');?></div>
                        <div class="text">
                            <?php echo ($Listing['gender'] == '2' ? __('Male') : __('Female'));?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($Data['birthday']) { ?>
                    <div class="profile-attr-small">
                        <div class="attr"><?php echo __('Birthday, Age');?></div>
                        <div class="text">
                            <?php echo dating($Data['birthday'],null,true);?>, 
                            <?php 
                            $from = new DateTime($Data['birthday']);
                            $to   = new DateTime('today'); 
                            echo $from->diff($to)->y;?> 
                            <?php echo __('years old');?></div>
                    </div>
                    <?php } ?>
                    <div class="nav-social">
                        <?php if($Data['social']['facebook']) { ?>
                        <a href="<?php echo 'https://www.facebook.com/'.$Data['social']['facebook'];?>" title="Facebook">
                            <svg class="icon">
                                <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#facebook';?>" />
                            </svg>
                        </a>
                        <?php } ?>
                        <?php if($Data['social']['twitter']) { ?>
                        <a href="<?php echo 'https://www.twitter.com/'.$Data['social']['twitter'];?>" title="Twitter">
                            <svg class="icon">
                                <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#twitter';?>" />
                            </svg>
                        </a>
                        <?php } ?>
                        <?php if($Data['social']['instagram']) { ?>
                        <a href="<?php echo 'https://www.instagram.com/'.$Data['social']['instagram'];?>" title="Instagram">
                            <svg class="icon">
                                <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#instagram';?>" />
                            </svg>
                        </a>
                        <?php } ?>
                        <?php if($Data['social']['youtube']) { ?>
                        <a href="<?php echo 'https://www.youtube.com/'.$Data['social']['youtube'];?>" title="Youtube">
                            <svg class="icon">
                                <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#youtube';?>" />
                            </svg>
                        </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="pl-lg-4">
                        <h1>
                            <?php echo $Listing['name'];?>
                        </h1>
                        <?php if($Listing['biography']) { ?>
                        <div class="profile-attr">
                            <div class="attr"><?php echo __('Biography');?></div>
                            <div class="text">
                                <?php echo $Listing['biography'];?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="profile-attr">
                            <div class="attr"><?php echo __('Acting');?> ( <?php echo count($Posts);?> )</div>
                            <!-- movies -->
                            <div class="row row-cols-5 my-3">
                                <?php foreach ($Posts as $Post) {?>
                                <div class="col">
                                    <div class="list-movie">
                                        <a href="<?php echo post($Post['content_id'],$Post['self'],$Post['type']);?>" class="list-media"> 
                                            <div class="play-btn">
                                                <svg class="icon">
                                                    <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#play';?>" />
                                                </svg>
                                            </div>
                                            <div class="media media-cover" data-src="<?php echo UPLOAD.'/cover/thumb-'.$Post['image'];?>"></div>
                                        </a>
                                        <div class="list-caption">
                                            <a href="<?php echo post($Post['content_id'],$Post['self'],$Post['type']);?>" class="list-title">
                                                <?php echo $Post['title'];?>
                                            </a>
                                            <a href="<?php echo post($Post['content_id'],$Post['self'],$Post['type']);?>" class="list-category">
                                                <?php echo $Post['character_name'];?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <!-- movies -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require PATH . '/theme/view/common/footer.php';?>