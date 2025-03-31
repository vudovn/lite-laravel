<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application.
    |
    */
    'name' => env('APP_NAME', 'LitePHP'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes.
    |
    */
    'environment' => env('APP_ENV', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application.
    |
    */
    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the framework to properly generate URLs.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application.
    |
    */
    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider.
    |
    */
    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application.
    |
    */
    'providers' => [
        // Application Service Providers...
    ],

    /*
    |--------------------------------------------------------------------------
    | View Directories
    |--------------------------------------------------------------------------
    |
    | List of directories where view files are located.
    |
    */
    'views' => [
        __DIR__ . '/../../resources/views',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Directory
    |--------------------------------------------------------------------------
    |
    | Path to the directory where cache files are stored.
    |
    */
    'cache_dir' => __DIR__ . '/../../storage/cache',

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    |
    | Session settings.
    |
    */
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'cookie' => 'vutoichoi_session',
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'http_only' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for standard alert notifications.
    |
    */
    'alerts' => [
        'duration' => 4000,           // Duration in milliseconds 
        'theme' => 'light',           // Light or dark theme
        'escape_html' => true,        // Escape HTML in messages
    ],
];