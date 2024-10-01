<?php
namespace App\Controllers;

use app\Models\ReturnedItem;
use core\Controller;
use core\Redirect;
use core\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller {

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index()
    {
        $returnedItems = ReturnedItem::leftJoin('borrowed_items', 'returned_items.borrowed_item_id', '=', 'borrowed_items.id')
            ->leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'returned_items.user_id', '=', 'users.id')
            ->select([
                'returned_items.*',
                'items.name AS item_name',
                'items.id as item_id',
                'categories.name AS category_name',
                'users.name AS user_name',
                'borrowed_items.borrowed_date',
            ])
            ->where('returned_items.status', '=', 'approved')->get();

        View::render('reports/index', [
            'returnedItems' => $returnedItems,
        ]);
    }

    public function exportToExcel()
    {
        $data = ReturnedItem::leftJoin('borrowed_items', 'returned_items.borrowed_item_id', '=', 'borrowed_items.id')
        ->leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
        ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
        ->leftJoin('users', 'returned_items.user_id', '=', 'users.id')
        ->select([
            'returned_items.id',
            'items.name AS item_name',
            'categories.name AS category_name',
            'users.name AS user_name',
            'borrowed_items.borrowed_date',
            'returned_items.returned_date',
        ])
        ->where('returned_items.status', '=', 'approved')->get();

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
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'returned_items.xlsx';
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
