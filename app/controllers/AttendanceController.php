<?php

namespace app\controllers;

use app\models\Attendance;
use core\Controller;
use core\Redirect;
use core\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            ->where('created_at', '>=', today())
            ->select([
                'attendance.*',
                'users.name AS user_name',
            ])
            ->paginate(10);

        View::render('attendance/index', [
            'attendance' => $attendance,
        ]);
    }

    public function exportToExcel()
    {
        $data = Attendance::leftJoin('users', 'attendance.user_id', '=', 'users.id')
            ->where('created_at', '>=', today())
            ->select([
                'attendance.id',
                'users.name AS user_name',
                'attendance.date',
                'attendance.time_in',
                'attendance.time_out',
            ])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'Date');
        $sheet->setCellValue('D1', 'Time In');
        $sheet->setCellValue('E1', 'Time Out');

        // Populate the data rows
        $row = 2;
        foreach ($data as $ri) {
            $sheet->setCellValue('A' . $row, $ri['id']);
            $sheet->setCellValue('B' . $row, $ri['user_name']);
            $sheet->setCellValue('C' . $row, $ri['date']);
            $sheet->setCellValue('D' . $row, $ri['time_in']);
            $sheet->setCellValue('E' . $row, $ri['time_out']);
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'attendance_' . date('Y-m-d') . '.xlsx';
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
