<?php
namespace App\Controllers;

use app\Models\User;
use core\Controller;
use core\Redirect;
use core\View;

class HomeController extends Controller {


    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index()
    {
         if (isset($_SESSION['user'])) {
            $user = User::find($_SESSION['user']);
            View::render('home', ['user' => $user]);
        } else {
            View::render('home');
        }
    }
}
