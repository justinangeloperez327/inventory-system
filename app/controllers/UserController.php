<?php

namespace app\Controllers;

use app\Models\User;
use core\Controller;
use core\Redirect;
use core\View;
use Endroid\QrCode\Builder\Builder;

class UserController extends Controller{

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index() {
        $users = User::orderby('id', 'desc')->paginate(10);

        View::render('users/index', ['users' => $users]);
    }

    public function create() {
        View::render('users/create');
    }

    public function store() {
        if (User::findBy('username', $_POST['username'])) {
            Redirect::back('Username already exists!');
        }

        User::create([
            'name' => $_POST['name'],
            'username' => $_POST['username'],
            'password' => password_hash(1, PASSWORD_DEFAULT),
            'role' => $_POST['role']
        ]);

        Redirect::to('users');
    }

    public function show($id) {
        $user = User::find($id);
        if ($user) {
            View::render('users/show', ['user' => $user]);
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function edit($id) {
        $user = User::find($id);
        if ($user) {
            View::render('users/edit', ['user' => $user]);
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function update($id) {
        $user = User::find($id);
        if ($user) {
            User::update($id, [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ]);
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function destroy($id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function restore($id) {
        $user = User::find($id);
        if ($user) {
            $user->restore();
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function generateQRCode($id) {
        $result = Builder::create()
            ->data($id)
            ->size(300)
            ->margin(10)
            ->build();
        
        header('Content-Type: ' . $result->getMimeType());
        
        // Output the QR code image
        echo $result->getString();
    }
}
