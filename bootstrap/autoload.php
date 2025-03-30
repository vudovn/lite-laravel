<?php

// Use Composer's autoloader if available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

// Custom autoloader for our framework
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/../' . $class . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load helper functions
require_once __DIR__ . '/../Framework/helpers.php';
