<?php

use Illuminate\Support\Facades\Route;

// * {BASE_URL}/api/v1/stan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Stan'
    ]);
});

/* Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:stan'])->group(function () {
    //
}); */
