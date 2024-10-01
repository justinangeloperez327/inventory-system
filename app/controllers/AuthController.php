<?php

namespace app\Controllers;

use app\Models\User;
use core\Controller;
use core\Redirect;
use core\Session;
use core\View;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                $user = User::findBy('username', $username);

                if (!$user || !password_verify($password, $user['password'])) {
                    Redirect::back('Wrong username or password');
                }

                Session::set('authenticated', true);
                Session::set('user_id', $user['id']);
                Session::set('user_role', $user['role']);
                Session::set('user_name', $user['name']);

                Redirect::to('dashboard');
            } catch (Exception $e) {
                Redirect::back('Error logging in: ' . $e->getMessage());
            }
        } else {
            if (authenticated()) {
                $user = Session::get('user');
                if ($user['role'] === 'admin') {
                    Redirect::to('admin-dashboard');
                } else {
                    Redirect::to('user-dashboard');
                }
            } else {
                View::render('auth/login');
            }
        }
    }

    public function logout()
    {
        Session::destroy();
        Redirect::to('');
    }

}
