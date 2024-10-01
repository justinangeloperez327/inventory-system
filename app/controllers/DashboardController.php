<?php

namespace app\Controllers;

use core\Controller;
use core\Redirect;
use core\View;

class DashboardController extends Controller {
    
    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }

    public function index()
    {
        if ($_SESSION['user_role'] == 'admin') {
            $this->admin();
        } else {
            $this->user();
        }
    }

    public function admin()
    {
        View::render('dashboard/admin');
    }

    public function user()
    {
        View::render('dashboard/user');
    }
}
