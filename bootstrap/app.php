<?php

use Framework\Application;
use Framework\Config;
use Framework\Router;
use Framework\Environment;
use Framework\Database\DatabaseManager;

// Load environment variables
Environment::load(dirname(__DIR__));

// Create the application instance
$app = new Application();

// Make the app available globally
global $app;

// Load configuration
$app->singleton('config', function () {
    return new Config(__DIR__ . '/../config');
});

// Register app configuration 
$app->bind('app.config', function () {
    return require __DIR__ . '/../config/app/config.php';
});

// Set up database
$app->singleton('db', function () use ($app) {
    return new DatabaseManager($app->resolve('config'));
});

// Set up the router
$app->singleton('router', function () use ($app) {
    return new Router($app);
});

// Register paths
$app->bind('paths.base', function () {
    return dirname(__DIR__);
});

$app->bind('paths.public', function () {
    return dirname(__DIR__) . '/public';
});

$app->bind('paths.config', function () {
    return dirname(__DIR__) . '/config';
});

$app->bind('paths.storage', function () {
    return dirname(__DIR__) . '/storage';
});

$app->bind('paths.resources', function () {
    return dirname(__DIR__) . '/resources';
});

// Set up the view
$app->singleton('view', function () use ($app) {
    return new \Framework\View(view_path());
});

// Load framework helpers
require_once __DIR__ . '/helpers.php';

// Load application helpers
if (is_dir(__DIR__ . '/../app/Helpers')) {
    foreach (glob(__DIR__ . '/../app/Helpers/*.php') as $helperFile) {
        require_once $helperFile;
    }
}

// Register middleware
$app->registerMiddleware([
    // List of global middleware - to be registered when needed
    \App\Middleware\FlashSessionMiddleware::class,
]);

// Load routes
require_once __DIR__ . '/../routes/web.php';
if (file_exists(__DIR__ . '/../routes/api.php')) {
    require_once __DIR__ . '/../routes/api.php';
}

// Set error handling based on environment
if (env('APP_ENV', 'production') === 'production') {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

return $app;
