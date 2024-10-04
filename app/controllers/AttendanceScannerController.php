<?php

namespace app\Controllers;

use app\Models\Attendance;
use app\Models\User;
use core\Controller;
use core\Response;
use core\View;

class AttendanceScannerController extends Controller
{
    public function index()
    {
        View::render('attendance-scanner/index');
    }

    public function capture()
    {
        $user = User::findBy('id', $_POST['qrCodeData']);

        if (!$user) {
            Response::json(['success' => false, 'message' => 'User not found'], 404);
        }
        
        $today = date('Y-m-d');

        $attendance = Attendance::where('user_id', '=', $user['id'])
            ->where('date', '=',$today)
            ->first();

        if (!$attendance) {

            Attendance::create([
                'user_id' => $user['id'],
                'date' => date('Y-m-d'),
                'time_in' => date('H:i:s'),
            ]);

            Response::json([
                'success' => true, 
                'message' => 'Time in successfully', 
                'attendance' => $attendance
            ]);
        } else {

            $attendance = Attendance::where('user_id', '=', $user['id'])
                ->where('date', '=',$today)
                ->first();

            Attendance::update($attendance['id'], [
                'time_out' => date('H:i:s'),
            ]);

            Response::json([
                'success' => true, 
                'message' => 'Time out successfully', 
                'attendance' => $attendance
            ]);
        }

       
    }
}