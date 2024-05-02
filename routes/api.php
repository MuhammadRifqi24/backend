<?php

use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang'
    ]);
});
Route::post('auth/login', [API\AuthController::class, 'login']);
Route::post('auth/register-owner', [API\RegisterController::class, 'registerOwner']);
Route::get('auth/verify-email', [API\AuthController::class, 'getVerifyEmail']);
Route::post('auth/verify-email', [API\AuthController::class, 'verifyEmail']);
Route::post('auth/verify-email-owner', [API\AuthController::class, 'verifyEmailOwner']);

Route::middleware(['auth:sanctum', 'checkVerifyEmail'])->group(function () {
    Route::get('auth/profile', [API\AuthController::class, 'profile']);
    Route::post('auth/logout', [API\AuthController::class, 'destroy'])->withoutMiddleware('checkVerifyEmail');

    Route::post('auth/register-manager', [API\RegisterController::class, 'registerManager'])->middleware('checkRole:owner');
});
