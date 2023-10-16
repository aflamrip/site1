<?php require PATH . '/theme/view/common/header.php';?>
<div class="flex-fill">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo APP.'/collections';?>"><?php echo __('Collections');?></a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $Listing['name'];?>
            </li>
        </ol>
    </nav>
    <?php echo ads($Ads,3,'mb-3');?>
    <div class="collection-detail">
        <form method="post">
            <input type="hidden" name="_ACTION" value="save">
            <input type="hidden" name="_TOKEN" value="<?php echo $Token;?>">
            <h1>
                <?php echo $Listing['name'];?>
            </h1>
            <input type="text" name="name" class="form-control form-control-lg" placeholder="<?php echo __('Name');?>" value="<?php echo $Listing['name'];?>" required="true">
            <div class="collection-footer">
                <a href="<?php echo profile($Listing['id'],$Listing['username']);?>" class="user">
                    <?php echo $Listing['username'];?>
                </a>
                <span>, <?php echo __('by');?>
                    <?php echo timeago($Listing['created']);?> <?php echo __('created');?></span>
                <?php if($AuthUser['id'] == $Listing['user_id']) { ?>
                <a href="#" class="edit"><?php echo __('Edit');?></a>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-theme"><?php echo __('Save Changes');?></button>
            <!-- movies -->
            <div class="row row-cols-md-6 row-cols-2">
                <?php foreach ($Collections as $Collection) {?>
                <div class="col">
                    <div class="list-movie">
                        <input class="d-none" name="post[]" type="checkbox" value="<?php echo $Collection['id'];?>">
                        <a href="<?php echo post($Collection['content_id'],$Collection['self'],$Collection['type']);?>" class="list-media">
                            <div class="play-btn">
                                <svg class="icon">
                                    <use xlink:href="<?php echo ASSETS.'/img/sprite.svg#play';?>" />
                                </svg>
                            </div>
                            <div class="media media-cover" data-src="<?php echo UPLOAD.'/cover/thumb-'.$Collection['image'];?>"></div>
                        </a>
                        <div class="list-caption">
                            <a href="<?php echo post($Collection['content_id'],$Collection['self'],$Collection['type']);?>" class="list-title">
                                <?php echo $Collection['title'];?>
                            </a>
                            <a href="<?php echo APP.'/category/'.$Collection['category_self'];?>" class="list-category">
                                <?php echo $Collection['name'];?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- movies -->
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).on('click', '.collection-edit .list-movie', function(e) {
    if ($(this).find('[type="checkbox"]').is(":checked")) {
        $(this).find('[type="checkbox"]').prop("checked", false);
        $(this).removeClass("checked");
    } else {
        $(this).find('[type="checkbox"]').prop("checked", true);
        $(this).addClass("checked");
    }
    return false;
});
$(document).on('click', '.edit', function(e) {

    if ($('.collection-detail').hasClass('collection-edit')) {
        $(this).text('<?php echo __('Edit');?>');
    } else {
        $(this).text('<?php echo __('Cancel');?>');
    }
    $('.collection-detail').toggleClass('collection-edit');
    return false;
});
</script>
<?php require PATH . '/theme/view/common/footer.php';?>