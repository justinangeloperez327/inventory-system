<?php
namespace App\Controllers;

use core\Controller;
use core\View;

class ErrorController extends Controller {
    
    public function notFound()
    {
        View::render('errors/404');
    }
}
