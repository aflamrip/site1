<div class="form-group">
    <label class="custom-label">TheMovieDB <?php echo __('Api Key');?></label>
    <input type="text" name="data[<?php echo $key?>][tmdb_api]" value="<?php echo get($Settings,'data.tmdb_api',$key);?>" class="form-control" placeholder="TheMovieDB <?php echo __('Api Key');?>">
</div> 
<div class="form-group">
    <label class="custom-label">TheMovieDB <?php echo __('Language');?></label>
    <input type="text" name="data[<?php echo $key?>][tmdb_language]" value="<?php echo get($Settings,'data.tmdb_language',$key);?>" class="form-control" placeholder="TheMovieDB <?php echo __('Language');?>">
</div> 
<div class="form-group mt-3">
    <label class="custom-label">Onesignal <?php echo __('Api ID');?></label>
    <input type="text" name="data[<?php echo $key?>][onesignal_id]" value="<?php echo get($Settings,'data.onesignal_id',$key);?>" class="form-control" placeholder="Onesignal <?php echo __('Api ID');?>">
</div>  
<div class="form-group mt-3">
    <label class="custom-label">Onesignal <?php echo __('Api Key');?></label>
    <input type="text" name="data[<?php echo $key?>][onesignal_key]" value="<?php echo get($Settings,'data.onesignal_key',$key);?>" class="form-control" placeholder="Onesignal <?php echo __('Api Key');?>">
</div>  