<?php

namespace app\controllers;

use app\models\Attendance;
use app\models\User;
use core\Controller;
use core\Redirect;
use core\Response;
use core\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }

    public function index()
    {
        $users = User::orderby('id', 'desc')->where('id', '!=', 1)->paginate(10);

        View::render('users/index', ['users' => $users]);
    }

    public function create()
    {
        View::render('users/create');
    }

    public function store()
    {
        if (User::findBy('username', $_POST['username'])) {
            Redirect::back('Username already exists!');
        }

        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        // validate username for speical characters
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            Redirect::to('users/create', 'Username can only contain letters, numbers, and underscores!');
        }

        User::create([
            'name' => $_POST['name'],
            'username' => $_POST['username'],
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'default_password' => $password,
            'role' => $_POST['role']
        ]);

        Redirect::to('users');
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            View::render('users/show', ['user' => $user]);
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            View::render('users/edit', ['user' => $user]);
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function update($id)
    {
        $user = User::find($id);
        if ($user) {
            User::update($id, [
                'name' => $_POST['name'],
                'username' => $_POST['username']
            ]);
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function restore($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->restore();
            Redirect::back('users');
        } else {
            View::render('users/index', ['message' => 'User not found!']);
        }
    }

    public function passwordReset($id)
    {
        $user = User::find($id);

        if (!$user) {
            Response::json(['success' => false, 'message' => 'User not found!'], 404);
        }

        User::update($id, [
            'password' => password_hash(1, PASSWORD_DEFAULT)
        ]);

        Response::json(['success' => true, 'message' => 'Password reset successfully']);
    }

    public function logs($id)
    {
        $user = User::find($id);

        if (!$user) {
            View::render('users/index', ['message' => 'User not found!']);
        }

        $attendances = Attendance::where('user_id', '=', $id)->orderBy('date', 'desc')->paginate(10);

        View::render('users/logs', ['attendances' => $attendances, 'user' => $user]);
    }

    public function exportToExcel($id)
    {
        $user = User::find($id);

        if (!$user) {
            View::render('users/index', ['message' => 'User not found!']);
        }

        $attendances = Attendance::where('user_id', '=', $id)->orderBy('date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Date');
        $sheet->setCellValue('B1', 'Time In');
        $sheet->setCellValue('C1', 'Time Out');

        $row = 2;
        foreach ($attendances as $attendance) {
            $sheet->setCellValue('A' . $row, $attendance['date']);
            $sheet->setCellValue('B' . $row, $attendance['time_in']);
            $sheet->setCellValue('C' . $row, $attendance['time_out']);
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'users' . $user['id'] . '.xlsx';
        $writer->save($fileName);

        // Set headers to prompt download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        readfile($fileName);

        // Delete the file after download
        unlink($fileName);
    }
}
