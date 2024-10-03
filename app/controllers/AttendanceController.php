<?php

namespace app\Controllers;

use app\Models\User;
use core\Controller;
use core\Response;
use core\View;

class AttendanceController extends Controller
{
    public function index()
    {
        View::render('attendance/camera-scanner');
    }

    public function capture()
    {
        $attendance = User::findBy('id', $_POST['qrCodeData']);

        if (!$attendance) {
            Response::json(['success' => false, 'message' => 'User not found'], 404);
        }

        Response::json(['success' => true, 'message' => 'Attendance captured successfully', 'data' => ['user' => $attendance]]);
    }
}