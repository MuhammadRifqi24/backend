<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Owner;

// * {BASE_URL}/api/v1/owner
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Stan'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:owner'])->group(function () {
    Route::get('product/index', [Owner\ProductController::class, 'index']);
    Route::post('product/insert', [Owner\ProductController::class, 'insert']);
    Route::post('product/update', [Owner\ProductController::class, 'update']);
    Route::delete('product/delete', [Owner\ProductController::class, 'destroy']);

    Route::get('cafe/index', [Owner\CafeController::class, 'index']);
    Route::post('cafe/update', [Owner\CafeController::class, 'update']);
});