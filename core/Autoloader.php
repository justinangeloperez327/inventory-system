<?php

// Autoload the necessary classes
spl_autoload_register(function ($class) {
    // Replace the namespace prefix with the base directory
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../';  // Base directory for the namespace prefix

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // If not, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    // Replace the namespace separators with directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});