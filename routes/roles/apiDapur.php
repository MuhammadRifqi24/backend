<?php

use Illuminate\Support\Facades\Route;

// * {BASE_URL}/api/v1/dapur
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pegawai Dapur'
    ]);
});

/* Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:stan'])->group(function () {
    //
}); */
