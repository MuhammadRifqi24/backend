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
    Route::get('product/view/{uuid}', [Owner\ProductController::class, 'getByUUID']);
    Route::post('product/insert', [Owner\ProductController::class, 'insert']);
    Route::post('product/update', [Owner\ProductController::class, 'update']);
    Route::delete('product/delete/{uuid}', [Owner\ProductController::class, 'destroy']);

    Route::post('stock/increment/{uuid}', [Owner\StockController::class, 'incrementData']);
    Route::post('stock/decrement/{uuid}', [Owner\StockController::class, 'decrementData']);

    Route::get('cafe/index', [Owner\CafeController::class, 'index']);
    Route::post('cafe/update', [Owner\CafeController::class, 'update']);
    Route::get('cafe/management', [Owner\CafeController::class, 'management']);

    Route::get('table/index', [Owner\TableInfoController::class, 'index']);
    Route::post('table/insert', [Owner\TableInfoController::class, 'insert']);
    Route::delete('table/delete/{uuid}', [Owner\TableInfoController::class, 'destroy']);

    Route::get('raw-material-category/index', [Owner\RawMaterialCategoryController::class, 'index']);
    Route::get('raw-material-category/{uuid}', [Owner\RawMaterialCategoryController::class, 'find']);
    Route::post('raw-material-category/insert', [Owner\RawMaterialCategoryController::class, 'insert']);
    Route::post('raw-material-category/update', [Owner\RawMaterialCategoryController::class, 'update']);
    Route::delete('raw-material-category/delete/{uuid}', [Owner\RawMaterialCategoryController::class, 'destroy']);
    Route::get('raw-material-category/view/{uuid}', [Owner\RawMaterialCategoryController::class, 'getByUUID']);

    Route::get('raw-material/index', [Owner\RawMaterialController::class, 'index']);
    Route::post('raw-material/insert', [Owner\RawMaterialController::class, 'insert']);
    Route::post('raw-material/update', [Owner\RawMaterialController::class, 'update']);
    Route::delete('raw-material/delete/{uuid}', [Owner\RawMaterialController::class, 'destroy']);
    Route::get('raw-material/view/{uuid}', [Owner\RawMaterialController::class, 'getByUUID']);

    Route::post('raw-material-stock/increment/{uuid}', [Owner\RawMaterialStockController::class, 'incrementData']);
    Route::post('raw-material-stock/decrement/{uuid}', [Owner\RawMaterialStockController::class, 'decrementData']);

    Route::get('category/index', [Owner\CategoryController::class, 'index']);
    Route::post('category/insert', [Owner\CategoryController::class, 'insert']);
    Route::post('category/update', [Owner\CategoryController::class, 'update']);
    Route::delete('category/delete/{uuid}', [Owner\CategoryController::class, 'destroy']);
    Route::get('category/view/{uuid}', [Owner\CategoryController::class, 'getByUUID']);

    Route::get('order/index', [Owner\OrderController::class, 'index']);
    Route::get('order/view/{uuid}', [Owner\OrderController::class, 'getByUUID']);

    Route::get('raw-material-stock-in/index', [Owner\RawMaterialStockInController::class, 'index']);
    Route::get('raw-material-stock-out/index', [Owner\RawMaterialStockOutController::class, 'index']);
});