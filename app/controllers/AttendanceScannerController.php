<?php

namespace app\controllers;

use app\models\Attendance;
use app\models\User;
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
            ->where('date', '=', $today)
            ->first();

        if ($attendance) {
            $attendance = Attendance::where('user_id', '=', $user['id'])
                ->where('date', '=', $today)
                ->whereNotNull('time_in')
                ->first();

            if ($attendance) {
                Attendance::update($attendance['id'], [
                    'time_out' => (new \DateTime('now', new \DateTimeZone('Asia/Manila')))->format('H:i:s'),
                ]);

                Response::json([
                    'success' => true,
                    'message' => 'Time out successfully',
                    'attendance' => $attendance
                ]);
            }
        }

        //time in
        Attendance::create([
            'user_id' => $user['id'],
            'date' => date('Y-m-d'),
            'time_in' => (new \DateTime('now', new \DateTimeZone('Asia/Manila')))->format('H:i:s'),
        ]);

        Response::json([
            'success' => true,
            'message' => 'Time in successfully',
            'attendance' => $attendance
        ]);
    }
}
