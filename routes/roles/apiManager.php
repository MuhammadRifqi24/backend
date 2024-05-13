<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Manager;

// * {BASE_URL}/api/v1/manager
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Manager'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:manager'])->group(function () {
    Route::get('product/index', [Manager\ProductController::class, 'index']);
    Route::post('product/insert', [Manager\ProductController::class, 'insert']);
    Route::post('product/update', [Manager\ProductController::class, 'update']);
    Route::delete('product/delete', [Manager\ProductController::class, 'destroy']);

    Route::post('stock/increment/{uuid}', [Manager\StockController::class, 'incrementData']);
    Route::post('stock/decrement/{uuid}', [Manager\StockController::class, 'decrementData']);

    Route::get('cafe/index', [Manager\CafeController::class, 'index']);
    Route::post('cafe/update', [Manager\CafeController::class, 'update']);
});
