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
    Route::get('product/view/{uuid}', [Manager\ProductController::class, 'getByUUID']);
    Route::post('product/insert', [Manager\ProductController::class, 'insert']);
    Route::post('product/update', [Manager\ProductController::class, 'update']);
    Route::delete('product/delete/{uuid}', [Manager\ProductController::class, 'destroy']);

    Route::post('stock/increment/{uuid}', [Manager\StockController::class, 'incrementData']);
    Route::post('stock/decrement/{uuid}', [Manager\StockController::class, 'decrementData']);

    Route::get('cafe/index', [Manager\CafeController::class, 'index']);
    Route::post('cafe/update', [Manager\CafeController::class, 'update']);
    Route::get('cafe/management', [Manager\CafeController::class, 'management']);

    // Route::get('table-info/index', [Manager\TableInfoController::class, 'index']);
    // Route::post('table-info/insert', [Manager\TableInfoController::class, 'insert']);
    // Route::post('table-info/update', [Manager\TableInfoController::class, 'update']);
    // Route::post('table-info/book-table', [Manager\TableInfoController::class, 'bookTable']);
    // Route::post('table-info/finish-table', [Manager\TableInfoController::class, 'finishTable']);
    // Route::delete('table-info/delete', [Manager\TableInfoController::class, 'destroy']);

    Route::get('table/index', [Manager\TableInfoController::class, 'index']);
    Route::post('table/insert', [Manager\TableInfoController::class, 'insert']);
    Route::delete('table/delete/{uuid}', [Manager\TableInfoController::class, 'destroy']);

    Route::get('raw-material-category/index', [Manager\RawMaterialCategoryController::class, 'index']);
    Route::get('raw-material-category/{uuid}', [Manager\RawMaterialCategoryController::class, 'find']);
    Route::post('raw-material-category/insert', [Manager\RawMaterialCategoryController::class, 'insert']);
    Route::post('raw-material-category/update', [Manager\RawMaterialCategoryController::class, 'update']);
    Route::delete('raw-material-category/delete/{uuid}', [Manager\RawMaterialCategoryController::class, 'destroy']);
    Route::get('raw-material-category/view/{uuid}', [Manager\RawMaterialCategoryController::class, 'getByUUID']);

    Route::get('raw-material/index', [Manager\RawMaterialController::class, 'index']);
    Route::post('raw-material/insert', [Manager\RawMaterialController::class, 'insert']);
    Route::post('raw-material/update', [Manager\RawMaterialController::class, 'update']);
    Route::delete('raw-material/delete/{uuid}', [Manager\RawMaterialController::class, 'destroy']);
    Route::get('raw-material/view/{uuid}', [Manager\RawMaterialController::class, 'getByUUID']);

    Route::post('raw-material-stock/increment/{uuid}', [Manager\RawMaterialStockController::class, 'incrementData']);
    Route::post('raw-material-stock/decrement/{uuid}', [Manager\RawMaterialStockController::class, 'decrementData']);

    Route::get('order/index', [Manager\OrderController::class, 'index']);
    Route::get('order/view/{uuid}', [Manager\OrderController::class, 'getByUUID']);

    Route::get('category/index', [Manager\CategoryController::class, 'index']);
    Route::post('category/insert', [Manager\CategoryController::class, 'insert']);
    Route::post('category/update', [Manager\CategoryController::class, 'update']);
    Route::delete('category/delete/{uuid}', [Manager\CategoryController::class, 'destroy']);
    Route::get('category/view/{uuid}', [Manager\CategoryController::class, 'getByUUID']);
});
