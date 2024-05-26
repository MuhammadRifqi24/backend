<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Dapur;

// * {BASE_URL}/api/v1/dapur
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pegawai Dapur'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:dapur'])->group(function () {
    Route::get('product/index', [Dapur\ProductController::class, 'index']);
    Route::post('product/insert', [Dapur\ProductController::class, 'insert']);
    Route::post('product/update', [Dapur\ProductController::class, 'update']);
    Route::delete('product/delete', [Dapur\ProductController::class, 'destroy']);
});
