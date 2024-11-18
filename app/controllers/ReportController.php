<?php

namespace App\controllers;

use app\models\BorrowedItem;
use app\models\ReturnedItem;
use core\Controller;
use core\Redirect;
use core\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }

    public function index()
    {
        $borrowedItems = BorrowedItem::leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('returned_items', 'borrowed_items.id', '=', 'returned_items.borrowed_item_id')
            ->leftJoin('users', 'borrowed_items.user_id', '=', 'users.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select([
                'borrowed_items.*',
                'items.name AS item_name',
                'items.id as item_id',
                'categories.name AS category_name',
                'users.name AS user_name',
                'returned_items.returned_date',
            ])
            ->get();

        View::render('reports/index', [
            'borrowedItems' => $borrowedItems,
        ]);
    }

    public function exportToExcel()
    {
        $data = BorrowedItem::leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('returned_items', 'borrowed_items.id', '=', 'returned_items.borrowed_item_id')
            ->leftJoin('users', 'borrowed_items.user_id', '=', 'users.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select([
                'borrowed_items.*',
                'items.name AS item_name',
                'items.id as item_id',
                'categories.name AS category_name',
                'users.name AS user_name',
                'returned_items.returned_date',
            ])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Item Name');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Borrowed By');
        $sheet->setCellValue('E1', 'Borrowed Date');
        $sheet->setCellValue('F1', 'Returned Date');

        // Populate the data rows
        $row = 2;
        foreach ($data as $ri) {
            $sheet->setCellValue('A' . $row, $ri['id']);
            $sheet->setCellValue('B' . $row, $ri['item_name']);
            $sheet->setCellValue('C' . $row, $ri['category_name']);
            $sheet->setCellValue('D' . $row, $ri['user_name']);
            $sheet->setCellValue('E' . $row, $ri['borrowed_date']);
            $sheet->setCellValue('F' . $row, $ri['returned_date']);
            $sheet->setCellValue('G' . $row, $ri['status']);
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'report_' . today() . '.xlsx';
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
