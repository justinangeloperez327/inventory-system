<?php

namespace core;

use Core\Session;

class Controller
{
    protected function isAuthenticated()
    {
        return Session::has('user');
    }
}
