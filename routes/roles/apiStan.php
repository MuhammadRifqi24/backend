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

    Route::get('category/index', [Stan\CategoryController::class, 'index']);
    Route::get('category/{uuid}', [Stan\CategoryController::class, 'find']);
    Route::post('category/insert', [Stan\CategoryController::class, 'insert']);
    Route::post('category/update', [Stan\CategoryController::class, 'update']);
    Route::delete('category/delete', [Stan\CategoryController::class, 'destroy']);

    Route::get('raw-material-category/index', [Stan\RawMaterialCategoryController::class, 'index']);
    Route::get('raw-material-category/{uuid}', [Stan\RawMaterialCategoryController::class, 'find']);
    Route::post('raw-material-category/insert', [Stan\RawMaterialCategoryController::class, 'insert']);
    Route::post('raw-material-category/update', [Stan\RawMaterialCategoryController::class, 'update']);
    Route::delete('raw-material-category/delete', [Stan\RawMaterialCategoryController::class, 'destroy']);

    Route::get('raw-material/index', [Stan\RawMaterialController::class, 'index']);
    Route::post('raw-material/insert', [Stan\RawMaterialController::class, 'insert']);
    Route::post('raw-material/update', [Stan\RawMaterialController::class, 'update']);
    Route::delete('raw-material/delete', [Stan\RawMaterialController::class, 'destroy']);

    Route::post('raw-material-stock/increment/{uuid}', [Stan\RawMaterialStockController::class, 'incrementData']);
    Route::post('raw-material-stock/decrement/{uuid}', [Stan\RawMaterialStockController::class, 'decrementData']);
});