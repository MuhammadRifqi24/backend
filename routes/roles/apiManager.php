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

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:manager,owner'])->group(function () {
    Route::get('product/index', [Manager\ProductController::class, 'index']);
    Route::post('product/insert', [Manager\ProductController::class, 'insert']);
});
