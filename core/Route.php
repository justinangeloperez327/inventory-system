<?php

namespace core;

class Route
{
    private static $routes = [];

    public static function get($uri, $callback)
    {
        self::$routes['GET'][$uri] = $callback;
    }

    public static function post($uri, $callback)
    {
        self::$routes['POST'][$uri] = $callback;
    }

    public static function resolve()
    {
        $uri = $_GET['url'] ?? '/';  // Default to '/' if no URL is provided
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes[$method] as $route => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
                array_shift($matches); // Remove the full match
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } elseif (is_array($callback)) {
                    $controllerClass = $callback[0];
                    $action = $callback[1];

                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();

                        if (method_exists($controllerInstance, $action)) {
                            call_user_func_array([$controllerInstance, $action], $matches);
                        } else {
                            echo "Error: Method $action not found in $controllerClass";
                        }
                    } else {
                        echo "Error: Controller $controllerClass not found";
                    }
                }
                return;
            }
        }

        echo "Error: Route $uri not found";
    }
}