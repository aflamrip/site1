<!DOCTYPE html>
<html class="app">

<head>
    <title>
        <?php echo __('Dashboard');?>
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS.'/css/app.css';?>">
    <style type="text/css">
    :root {
        --theme-color: <?php echo get($Settings, "data.dashboard", "theme")?>;
    }
    </style>
    <script type="text/javascript">
    var URL = "<?php echo APP?>";
    var ASSETS = "<?php echo APP.'/public/assets'?>";

    window.i18n = {
        'Deletion is successful': '<?php echo __("Deletion is successful");?>'
    };
    </script>
    <meta name="theme-color" content="#000">
    <link rel="shortcut icon" href="<?php echo LOCAL.'/'.get($Settings,'data.favicon','general').'?v='.VERSION;?>">
</head>

<body>