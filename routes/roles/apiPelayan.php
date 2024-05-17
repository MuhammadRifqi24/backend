<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Pelayan;

// * {BASE_URL}/api/v1/pelayan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pelayan'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:pelayan'])->group(function () {
    Route::get('table-info/index', [Pelayan\TableInfoController::class, 'index']);
    Route::post('table-info/insert', [Pelayan\TableInfoController::class, 'insert']);
    Route::post('table-info/update', [Pelayan\TableInfoController::class, 'update']);
    Route::post('table-info/book-table', [Pelayan\TableInfoController::class, 'bookTable']);
    Route::post('table-info/finish-table', [Pelayan\TableInfoController::class, 'finishTable']);
    Route::delete('table-info/delete', [Pelayan\TableInfoController::class, 'destroy']);
});
