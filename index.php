<?php

require 'vendor/autoload.php'; // Ensure this path is correct


// Autoloader: Automatically load all classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . "$class.php";
    if (file_exists($file)) {
        require_once $file;
    }
});

// Error handling (optional if using a custom error handler)

use core\ErrorHandler;
ErrorHandler::register();  // Register the error handler (if implemented)

// Include view helper functions globally
require_once __DIR__ . '/core/view_directives.php';

// Use the necessary core components
use core\App;

use core\Session;
// Start session
Session::start();  // Initialize session for route protection

// Include routes
require_once __DIR__ . '/routes.php';

App::run();
