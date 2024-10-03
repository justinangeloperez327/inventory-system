<?php

namespace app\Controllers;

use app\Models\User;
use core\Controller;
use core\Redirect;
use core\Session;
use core\View;
use Exception;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = User::find(Session::get('user_id'));

        View::render('profile/index', [
            'user' => $user
        ]);
    }

    public function updateName($id)
    {
        $user = User::find($id);

        if (!$user) {
            Redirect::to('not-found');
        }

        User::update($user['id'], [
            'name' => $_POST['name']
        ]);

        Session::set('user_name', $_POST['name']);

        Redirect::to('profile', 'Profile updated successfully');
    }

    public function updatePassword($id)
    {
        $user = User::find($id);
        if (!$user) {
            Redirect::to('not-found');
        }
        
        // check if current_password is confirmed
        if (!isset($_POST['current_password']) || !isset($_POST['confirm_current_password']) || !isset($_POST['new_password']) || !isset($_POST['confirm_new_password'])) {
            Redirect::back('Please fill all fields');
        }
        
        // check if confirm currenct password
        if ($_POST['current_password'] !== $_POST['confirm_current_password']) {
            Redirect::back('Please confirm current password');
        }

        // check if confirm currenct password
        if ($_POST['new_password'] !== $_POST['confirm_new_password']) {
            Redirect::back('Please confirm new password');
        }

        if (!password_verify($_POST['current_password'], $user['password'])) {
            Redirect::back('Current password is incorrect');
        }

        User::update($user['id'], [
            'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
        ]);

        Redirect::to('profile', 'Password updated successfully');
    }
}
