<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Stan;

// * {BASE_URL}/api/v1/stan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Stan'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:stan'])->group(function () {
    Route::get('product/index', [Stan\ProductController::class, 'index']);
    Route::post('product/insert', [Stan\ProductController::class, 'insert']);
    Route::post('product/update', [Stan\ProductController::class, 'update']);
    Route::delete('product/delete', [Stan\ProductController::class, 'destroy']);

    Route::post('stock/increment/{uuid}', [Stan\StockController::class, 'incrementData']);
    Route::post('stock/decrement/{uuid}', [Stan\StockController::class, 'decrementData']);
});