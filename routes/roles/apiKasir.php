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
    // Route::get('product/index', [Kasir\ProductController::class, 'index']);
    // Route::post('product/insert', [Kasir\ProductController::class, 'insert']);
    // Route::post('product/update', [Kasir\ProductController::class, 'update']);
    // Route::delete('product/delete', [Kasir\ProductController::class, 'destroy']);

    Route::get('order/index', [Kasir\OrderController::class, 'index']);
    Route::get('order/view/{uuid}', [Kasir\OrderController::class, 'getByUUID']);
    Route::post('order/status', [Kasir\OrderController::class, 'updateOrderStatus']);
    Route::post('order/payment-status', [Kasir\OrderController::class, 'updatePaymentStatus']);
});
