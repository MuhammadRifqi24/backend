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
});