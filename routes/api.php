<?php

use App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return response()->json([
            'status' => true,
            'message' => 'Selamat Datang'
        ]);
    });

    Route::post('auth/login', [API\AuthController::class, 'login']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'checkVerifyEmail']], function () {
    Route::post('auth/logout', [API\AuthController::class, 'destroy'])->withoutMiddleware(['checkVerifyEmail']);
});
