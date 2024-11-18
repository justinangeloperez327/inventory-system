<?php

namespace App\controllers;

use core\Controller;
use core\View;

class ErrorController extends Controller
{

    public function notFound()
    {
        View::render('errors/404');
    }
}
