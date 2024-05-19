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

    Route::get('table-info/index', [Manager\TableInfoController::class, 'index']);
    Route::post('table-info/insert', [Manager\TableInfoController::class, 'insert']);
    Route::post('table-info/update', [Manager\TableInfoController::class, 'update']);
    Route::post('table-info/book-table', [Manager\TableInfoController::class, 'bookTable']);
    Route::post('table-info/finish-table', [Manager\TableInfoController::class, 'finishTable']);
    Route::delete('table-info/delete', [Manager\TableInfoController::class, 'destroy']);
});
