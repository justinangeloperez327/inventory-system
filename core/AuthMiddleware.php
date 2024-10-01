<?php

namespace core;

use core\Session;
use core\Redirect;

class AuthMiddleware
{
    // Check if user is authenticated
    public static function check()
    {
        if (!Session::has('user')) {
            // If user is not logged in, redirect to the login page
            Redirect::to('auth/login');
        }
    }

    // Check if the user is an admin
    public static function checkAdmin()
    {
        if (!Session::has('user') || Session::get('user')['role'] !== 'admin') {
            // If the user is not an admin, redirect to the login page
            Redirect::to('auth/login');
        }
    }
}
