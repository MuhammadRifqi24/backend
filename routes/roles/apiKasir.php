<?php

use Illuminate\Support\Facades\Route;

// * {BASE_URL}/api/v1/kasir
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Kasir'
    ]);
});

/* Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:kasir'])->group(function () {
    //
}); */
