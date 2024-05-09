<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Kasir;

// * {BASE_URL}/api/v1/kasir
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Kasir'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:kasir'])->group(function () {
    Route::get('product/index', [Stan\ProductController::class, 'index']);
    Route::post('product/insert', [Stan\ProductController::class, 'insert']);
    Route::post('product/update', [Stan\ProductController::class, 'update']);
    Route::delete('product/delete', [Stan\ProductController::class, 'destroy']);
});
