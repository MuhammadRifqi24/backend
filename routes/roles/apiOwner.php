<?php

use App\Http\Controllers\API\Owner\ProductController;
use Illuminate\Support\Facades\Route;

// * {BASE_URL}/api/v1/
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pegawai Owner'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:owner'])->group(function () { 
    Route::post('product/insert', [ProductController::class, 'store']);
});