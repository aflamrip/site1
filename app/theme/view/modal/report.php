<div class="modal-content">
    <div class="modal-body">
        <form method="post">
            <input type="hidden" name="_TOKEN" value="<?php echo $Token;?>">
            <input type="hidden" name="content_id" value="<?php echo Input::cleaner($_GET['id']);?>">
            <div class="form-group">
                <label class="custom-label"><?php echo __('Report');?></label>
                <?php require PATH . '/config/array.config.php';?>
                <select name="report_id" class="custom-select" required="true">
                    <option value=""><?php echo __('Report');?></option>
                    <?php foreach ($Reports as $Value => $Key) { ?>
                    <option value="<?php echo $Value;?>">
                        <?php echo $Key;?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label class="custom-label"><?php echo __('Description');?></label>
                <textarea name="body" class="form-control" placeholder="<?php echo __('Could you please give some detail about the problem ?');?>"></textarea>
            </div>
            <button type="submit" class="btn btn-theme btn-block"><?php echo __('Submit');?></button>
        </form>
    </div>
</div>
<script type="text/javascript">
$(".modal form").submit(function() {
    $.ajax({
        url: _URL + '/ajax/report',
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function(resp) {
            Snackbar.show({ text: resp.text, customClass: 'bg-' + resp.status });
            $('.modal').modal('hide');
        }
    });
    return false;
});
</script>