<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views.
    |
    */
    'paths' => [
        __DIR__ . '/../../resources/views',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled template files should be
    | stored. This is useful for optimizing performance by not having to
    | parse templates on each load.
    |
    */
    'compiled' => __DIR__ . '/../../storage/cache/views',

    /*
    |--------------------------------------------------------------------------
    | View Debug Mode
    |--------------------------------------------------------------------------
    |
    | When debugging is enabled, detailed error messages are shown on view errors.
    |
    */
    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | View Extensions
    |--------------------------------------------------------------------------
    |
    | List of file extensions that will be checked when finding views
    |
    */
    'extensions' => [
        'php',
        'blade.php'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | The default layout to use if not specified in the view.
    |
    */
    'default_layout' => 'layouts.app',
];