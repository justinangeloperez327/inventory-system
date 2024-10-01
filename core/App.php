<?php

namespace core;

class App
{
    public static function run()
    {
        // Resolve the route and run the corresponding controller
        Route::resolve();
    }
}
