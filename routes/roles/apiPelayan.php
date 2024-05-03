<?php

use Illuminate\Support\Facades\Route;

// * {BASE_URL}/api/v1/pelayan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pelayan'
    ]);
});

/* Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:pelayan'])->group(function () {
    //
}); */
