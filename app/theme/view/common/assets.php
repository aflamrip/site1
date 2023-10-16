<!DOCTYPE html>
<html lang="<?php echo ACTIVE_LANG;?>">

<head>
    <title>
        <?php echo $Config['title'];?>
    </title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $Config['description'];?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index,follow" />
    <meta name="theme-color" content="#000">
    <meta name="HandheldFriendly" content="True">
    <meta http-equiv="cleartype" content="on">
    <?php if($Config['url']) { ?>
    <link rel="canonical" href="<?php echo $Config['url'];?>">
    <?php } ?>
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
    <link rel="dns-prefetch" href="//ajax.googleapis.com" />
    <link rel="dns-prefetch" href="//www.googletagmanager.com" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="dns-prefetch" href="//code.jquery.com" />
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link as="style" media="all" rel="stylesheet preload prefetch" href="<?php echo THEME.'/css/app.css?v='.VERSION;?>" type="text/css" crossorigin="anonymous" />
    <link rel="preload" href="<?php echo ASSETS.'/webfonts/inter/Inter-Regular.woff2';?>" as="font" crossorigin="anonymous" />
    <link rel="preload" href="<?php echo ASSETS.'/webfonts/inter/Inter-Medium.woff2';?>" as="font" crossorigin="anonymous" />
    <link rel="preload" href="<?php echo ASSETS.'/webfonts/inter/Inter-SemiBold.woff2';?>" as="font" crossorigin="anonymous" />
    <link rel="preload" href="<?php echo ASSETS.'/webfonts/inter/Inter-Bold.woff2';?>" as="font" crossorigin="anonymous" />
    <link rel="preload" href="<?php echo ASSETS.'/webfonts/inter/Inter-Black.woff2';?>" as="font" crossorigin="anonymous" />
    <script type="text/javascript">
        var _URL = "<?= APP?>";
        var _ASSETS = "<?= APP.'/public/assets'?>";
        <?php if ($AuthUser['id']) { ?>
        var _Auth = true;
        <?php } else { ?>
        var _Auth = false;
        <?php } ?>
        var __ = function(msgid) {
            return window.i18n[msgid] || msgid;
        };
        window.i18n = {
            'No comments yet': '<?php echo __("No comments yet");?>',
            'You must sign in': '<?php echo __("You must sign in");?>',
            'Follow': '<?php echo __("Follow");?>',
            'Following': '<?php echo __("Following");?>',
            'Show more': '<?php echo __("Show more");?>',
            'Show less': '<?php echo __("Show less");?>',
            'no results': '<?php echo __("no results");?>',
            'Results': '<?php echo __("Results");?>',
            'Comment': '<?php echo __("Comment");?>',
            'Actors': '<?php echo __("Actors");?>',
        };
        <?php if(get($Settings,'data.onesignal_id','api')) { ?>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(["init", {
            appId: '<?php echo get($Settings,"data.onesignal_id","api");?>',
            autoRegister: true
        }]);
        var OneSignal = window.OneSignal || [];
        if (OneSignal.installServiceWorker) {
            OneSignal.installServiceWorker();
        } else {
            if (navigator.serviceWorker) {
                navigator.serviceWorker.register('<?php echo APP."/OneSignalSDKWorker.js?appId=".get($Settings,"data.onesignal_id","api");?>');
            }
        }
        <?php } ?>
    </script>
    <style type="text/css">
    :root {
        --theme-color: <?php echo ($_COOKIE['--theme-color'] ? $_COOKIE['--theme-color']: get($Settings, "data.general", "theme"));
        ?>;
        --button-color: <?php echo ($_COOKIE['--button-color'] ? $_COOKIE['--button-color']: get($Settings, "data.button", "theme"));
        ?>;
        --background-color: <?php echo ($_COOKIE['--background-color'] ? urldecode($_COOKIE['--background-color']): get($Settings, "data.background", "theme"));
        ?>;
    }
    </style>
    <?php echo get($Settings,'data.headcode','general');?>
    <?php if($Config['share'] == true) { ?>
    <meta property="og:site_name" content="<?php echo APP;?>">
    <meta property="og:url" content="<?php echo $Config['url'];?>">
    <meta property="og:type" content="<?php echo $Config['ogtype'];?>">
    <meta property="og:title" content="<?php echo $Config['title'];?>">
    <meta property="og:description" content="<?php echo $Config['description'];?>">
    <?php if($Config['image']) { ?>
    <meta property="og:image" content="<?php echo $Config['image'];?>">
    <?php } ?>
    <meta name="twitter:card" content="summary">
    <?php if(get($Settings, "data.twitter", "social")) { ?>
    <meta name="twitter:site" content="@<?php echo get($Settings, " data.twitter", "social" );?>">
    <?php } ?>
    <meta name="twitter:title" content="<?php echo $Config['title'];?>">
    <meta name="twitter:url" content="<?php echo $Config['url'];?>">
    <meta name="twitter:description" content="<?php echo $Config['description'];?>">
    <?php if($Config['image']) { ?>
    <meta name="twitter:image" content="<?php echo $Config['image'];?>" />
    <?php } ?>
    <?php } ?>
    <link rel="shortcut icon" href="<?php echo LOCAL.'/'.get($Settings,'data.favicon','general').'?v='.VERSION;?>">
</head>

<body>
    <a class="skip-link d-none" href="#maincontent">Skip</a>