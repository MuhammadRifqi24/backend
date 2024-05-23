<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User;

// * {BASE_URL}/api/v1/pelayan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang User'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:user'])->group(function () {
    Route::get('table-info/cafe/{id}', [User\TableInfoController::class, 'index']);
    Route::post('table-info/book-table', [User\TableInfoController::class, 'bookTable']);
    Route::post('table-info/finish-table', [User\TableInfoController::class, 'finishTable']);

    Route::get('order/index', [User\OrderController::class, 'index']);
    Route::get('order/uuid/{uuid}', [User\OrderController::class, 'getByUUID']);
    Route::post('order/insert', [User\OrderController::class, 'insert']);
    Route::post('order/cancel', [User\OrderController::class, 'cancel']);
});