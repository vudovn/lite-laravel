<?php

/**
 * VuToiChoi - A lightweight Laravel-inspired PHP Framework
 */

define('FRAMEWORK_START', microtime(true));

// Define base path
$basePath = dirname(__DIR__);

// Load autoloader
require $basePath . '/bootstrap/autoload.php';

// Create the application instance
$app = require_once $basePath . '/bootstrap/app.php';

// Register paths
$app->bind('paths.base', function () use ($basePath) {
    return $basePath;
});

$app->bind('paths.public', function () {
    return __DIR__;
});

$app->bind('paths.config', function () use ($basePath) {
    return $basePath . '/config';
});

$app->bind('paths.storage', function () use ($basePath) {
    return $basePath . '/storage';
});

$app->bind('paths.resources', function () use ($basePath) {
    return $basePath . '/resources';
});

// Handle the request
$app->run();
