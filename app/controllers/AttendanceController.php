<?php

namespace app\Controllers;

use app\Models\Attendance;
use core\Controller;
use core\Redirect;
use core\View;

class AttendanceController extends Controller
{
    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index()
    {
        $attendance = Attendance::leftJoin('users', 'attendance.user_id', '=', 'users.id')
            ->select([
                'attendance.*',
                'users.name AS user_name',
            ])
            ->paginate(10);

        View::render('attendance/index', [
            'attendance' => $attendance,
        ]);
    }
}