<?php
$DashboardNav = array(
    array(
        'name' => __('Dashboard'),
        'icon' => 'fas fa-house',
        'url' => 'admin',
        'main' => 'main',
    ),
    array(
        'header' => __('General'),
    ),
    array(
        'name' => __('Movies'), 
        'url' => 'admin/movies',
        'main' => 'movies',
    ),
    array(
        'name' => __('Series'), 
        'url' => 'admin/series',
        'main' => 'series'
    ), 
    array(
        'name' => __('Episodes'), 
        'url' => 'admin/episodes',
        'main' => 'episodes'
    ), 
    array(
        'name' => __('TV Channels'), 
        'url' => 'admin/channels',
        'main' => 'channels'
    ), 
    array(
        'name' => __('Categories'), 
        'url' => 'admin/categories',
        'main' => 'categories',
    ),
    array(
        'name' => __('Actors'), 
        'url'   => 'admin/actors',
        'main'  => 'actors',
    ),
    array(
        'name' => __('Collections'), 
        'url'   => 'admin/collections',
        'main'  => 'collections',
    ),
    array(
        'header' => __('Community'),
    ),
    array(
        'name' => __('Users'), 
        'url' => 'admin/users',
        'main' => 'users',
    ),
    array( 
        'name' => __('Discussions'), 
        'url' => 'admin/discussions',
        'main' => 'discussions',
    ),
    array(
        'name' => __('Comments'), 
        'url' => 'admin/comments',
        'main' => 'comments',
    ),
    array(
        'name' => __('Reports'),
        'url' => 'admin/reports',
        'main' => 'reports',
    ),
    array(
        'header' => __('Other'),
    ),
    array(
        'name' => __('Slider'), 
        'url' => 'admin/slider',
        'main' => 'slider',
    ),
    array(
        'name' => __('Stories'), 
        'url' => 'admin/stories',
        'main' => 'stories',
    ),
    array(
        'name' => __('Tools'), 
        'url' => 'admin/tools',
        'main' => 'tools',
    ),
    array(
        'header' => __('Settings'),
    ),
            array(
                'name' => __('Languages'),
                'url'   => 'admin/languages',
                'main' => 'languages'
            ),
            array(
                'name' => __('Advertisements'),
                'url' => 'admin/ads',
                'main' => 'ads'
            ),
            array(
                'name' => __('Pages'),
                'url' => 'admin/pages',
                'main' => 'pages'
            ),
    array(
        'name' => __('Settings'),
        'url'   => 'admin/settings',
        'main' => 'settings',
        'sub' => array(
            array(
                'name' => __('General'),
                'url' => 'admin/settings',
                'main' => 'settings'
            ),
            array(
                'name' => __('Video Options'),
                'url' => 'admin/videos',
                'main' => 'settings'
            ),
            array(
                'name' => __('Countries'),
                'url' => 'admin/countries',
                'main' => 'settings'
            ),
        )
    ),
    array(
        'name'  => __('Logout'),
        'url'   => 'logout',
        'main'  => 'logout',
    ),
);