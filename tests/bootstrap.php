<?php

// Simple "Abc" Namespace autoloader
spl_autoload_register(function($className) {

    // Not-ABC
    if (substr($className, 0, 4) !== 'Abc\\') {
        return false;
    }

    // Transliterate and load directly
    $fileName = __DIR__ . '/../' . str_replace(
        '\\',
        DIRECTORY_SEPARATOR,
        $className
    ) . '.php';

    if (!file_exists($fileName)) {
        return false;
    }

    require $fileName;
});
