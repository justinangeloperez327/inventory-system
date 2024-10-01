<?php

namespace core;

class ErrorHandler
{
    public static function register()
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // Customize the error handling (log it, display a custom error page, etc.)
        echo "Error: [$errno] $errstr - $errfile:$errline";
    }

    public static function handleException($exception)
    {
        // Customize exception handling (log it, display a custom error page, etc.)
        echo "Exception: " . $exception->getMessage();
    }
}
