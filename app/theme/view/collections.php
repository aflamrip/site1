<?php require PATH . '/theme/view/common/header.php';?>
<div class="flex-fill">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo APP;?>"><?php echo __('Home');?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo __('Collections');?></li>
        </ol>
    </nav>
    <?php echo ads($Ads,3,'mb-3');?>
    <div class="d-flex">
        <div class="app-content">
            <div class="app-section">
                <div class="mb-3">
                    <div class="text-24 text-white font-weight-bold"><?php echo __('Collections');?></div>
                </div>
                <!-- movies -->
                <div class="row row-cols-lg-3 row-cols-1 list-grouped">
                    <?php foreach ($Listings as $Listing) { ?>
                    <div class="col">
                        <div class="list-collection" style="background-color: <?php echo $Listing['color'];?>;color: <?php echo $Listing['color'];?>"> 
                            <div class="list-caption">
                                <a href="<?php echo profile($Listing['user_id'],$Listing['username']);?>" class="list-user"><?php echo $Listing['username'];?></a>
                                <a href="<?php echo collection($Listing['id'],$Listing['self']);?>" class="list-title"><?php echo $Listing['name'];?></a>
                    <a href="<?php echo collection($Listing['id'],$Listing['self']);?>" class="list-desc"><?php echo $Listing['toplam'];?> <?php echo __('there is content');?></a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!-- movies -->
                <?php echo $Pagination;?>
                <div class="text-muted text-12">
                    <?php if($TotalRecord == 0) { ?>
                    <?php echo __('No content found');?>
                    <?php } else { ?>
                    <?php echo $TotalRecord;?> 
                    <?php echo __('contains content');?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require PATH . '/theme/view/common/footer.php';?>