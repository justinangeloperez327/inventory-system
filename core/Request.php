<?php

namespace Core;

class Request {

    // Get the request method (GET, POST, PUT, DELETE)
    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    // Get the requested URI
    public static function uri() {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    // Check if the request is a POST request
    public static function isPost() {
        return self::method() === 'POST';
    }

    // Check if the request is a PUT request
    public static function isPut() {
        return self::method() === 'PUT';
    }

    // Get all input from POST/PUT requests
    public static function all() {
        if (self::isPut()) {
            parse_str(file_get_contents("php://input"), $_PUT);
            return $_PUT;
        }
        return array_merge($_GET, $_POST);
    }

    // Get a specific input field from the request
    public static function input($key, $default = null) {
        $data = self::all();
        return $data[$key] ?? $default;
    }

    // Check if a particular input field exists
    public static function has($key) {
        $data = self::all();
        return isset($data[$key]);
    }

    // Get the value of a form parameter (for POST or PUT requests)
    public static function post($key, $default = null) {
        return self::input($key, $default);
    }
}
