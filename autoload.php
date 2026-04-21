<?php

spl_autoload_register(function ($class) {
    
    $sources = [
        'app/Entities/',
        'app/Repositories/',
        'app/Controllers/',
        'Config/'
    ];

    foreach ($sources as $source) {
        $file = __DIR__ . '/' . $source . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});