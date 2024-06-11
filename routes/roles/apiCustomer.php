<?php

use App\Http\Controllers\API\Customer\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Stan;

// * {BASE_URL}/api/v1/customer
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Customer'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:customer'])->group(function () {
    Route::get('product/index', [ProductController::class, 'index']);
});