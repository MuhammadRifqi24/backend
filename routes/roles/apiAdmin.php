<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin;

// * {BASE_URL}/api/v1/admin
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Admin'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:admin'])->group(function () {
    Route::get('cafe/index', [Admin\CafeController::class, 'index']);
    Route::get('cafe/view/{uuid}', [Admin\CafeController::class, 'getByUUID']);
    
    // Route::post('cafe/update-cafe-status', [Admin\CafeController::class, 'updateCafeStatus']);
});
