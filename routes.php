<?php

use app\Controllers\UserController;
use app\Controllers\ReturnedItemController;
use app\Controllers\RenewedItemController;
use app\Controllers\QrCodeController;
use app\Controllers\ProfileController;
use app\Controllers\ItemController;
use app\Controllers\DashboardController;
use app\Controllers\CategoryController;
use app\Controllers\BorrowedItemController;
use app\Controllers\AuthController;
use app\Controllers\AttendanceController;
use app\Controllers\AttendanceScannerController;
use App\Controllers\ReportController;
use App\Controllers\ErrorController;
use core\Route;


Route::get('/', [AuthController::class, 'login']);
Route::get('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('dashboard', [DashboardController::class, 'index']);

Route::get('items', [ItemController::class, 'index']);
Route::post('items', [ItemController::class, 'create']);
Route::post('items/{id}', [ItemController::class, 'update']);
Route::post('items/{id}/delete', [ItemController::class, 'delete']);

Route::get('borrowed-items', [BorrowedItemController::class, 'index']);
Route::post('borrowed-items', [BorrowedItemController::class, 'create']);
Route::post('borrowed-items/{id}/update', [BorrowedItemController::class, 'update']);
Route::post('borrowed-items/{id}/delete', [BorrowedItemController::class, 'delete']);

Route::get('returned-items', [ReturnedItemController::class, 'index']);
Route::get('returned-items/{id}', [ReturnedItemController::class, 'create']);
Route::post('returned-items/{id}/update', [ReturnedItemController::class, 'update']);

Route::get('renewed-items', [RenewedItemController::class, 'index']);
Route::get('renewed-items/{id}', [RenewedItemController::class, 'create']);
Route::post('renewed-items/{id}/update', [RenewedItemController::class, 'update']);

// Define category routes
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'create']);
Route::post('categories/{id}', [CategoryController::class, 'update']);
Route::post('categories/{id}/delete', [CategoryController::class, 'delete']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/create', [UserController::class, 'create']);
Route::post('users/store', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::get('users/{id}/logs', [UserController::class, 'logs']);
Route::get('users/{id}/export-to-excel', [UserController::class, 'exportToExcel']);


Route::get('reports', [ReportController::class, 'index']);
Route::get('reports/export-to-excel', [ReportController::class, 'exportToExcel']);

Route::get('not-found', [ErrorController::class, 'notFound']);

Route::get('create-admin-user', [AuthController::class, 'createAdminUser']);

Route::get('profile', [ProfileController::class, 'profile']);
Route::post('profile/{id}/update-name', [ProfileController::class, 'updateName']);
Route::post('profile/{id}/update-password', [ProfileController::class, 'updatePassword']);

Route::get('generate-qr-code/{id}', [QrCodeController::class, 'generate']);
Route::get('password-reset/{id}', [UserController::class, 'passwordReset']);

Route::get('attendance-scanner', [AttendanceScannerController::class, 'index']);
Route::post('attendance-capture', [AttendanceScannerController::class, 'capture']);

Route::get('attendance', [AttendanceController::class, 'index']);
Route::get('attendance/export-to-excel', [AttendanceController::class, 'exportToExcel']);