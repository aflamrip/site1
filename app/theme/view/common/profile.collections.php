<div class="row row-cols-lg-3 row-cols-md-2 list-grouped">
    <?php foreach ($Collections as $Collection) { ?>
    <div class="col">
        <div class="list-collection" style="background-color: <?php echo $Collection['color'];?>;color: <?php echo $Collection['color'];?>">
            <div class="list-caption"> 
                    <a href="<?php echo collection($Collection['id'],$Collection['self']);?>" class="list-title">
                        <?php echo $Collection['name'];?></a>
                    <a href="<?php echo collection($Collection['id'],$Collection['self']);?>" class="list-desc"><?php echo $Collection['toplam'];?> <?php echo __('there is content');?></a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>