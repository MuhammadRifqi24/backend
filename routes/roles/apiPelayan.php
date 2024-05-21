<?php

use App\Http\Controllers\API\Pelayan\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Pelayan;

// * {BASE_URL}/api/v1/pelayan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang Pelayan'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:pelayan'])->group(function () {
    Route::get('table-info/index', [Pelayan\TableInfoController::class, 'index']);
    Route::post('table-info/insert', [Pelayan\TableInfoController::class, 'insert']);
    Route::post('table-info/update', [Pelayan\TableInfoController::class, 'update']);
    Route::post('table-info/book-table', [Pelayan\TableInfoController::class, 'bookTable']);
    Route::post('table-info/finish-table', [Pelayan\TableInfoController::class, 'finishTable']);
    Route::delete('table-info/delete', [Pelayan\TableInfoController::class, 'destroy']);

    Route::get('order/index', [Pelayan\OrderController::class, 'index']);
    Route::get('order/uuid/{uuid}', [Pelayan\OrderController::class, 'getByUUID']);
    Route::get('order/user/{user_id}', [Pelayan\OrderController::class, 'getByUserId']);
    Route::get('order/table/{table_info_id}', [Pelayan\OrderController::class, 'getByTableInfoId']);
    Route::post('order/insert', [Pelayan\OrderController::class, 'insert']);
    Route::post('order/update', [Pelayan\OrderController::class, 'update']);
    Route::post('order/status', [Pelayan\OrderController::class, 'updateOrderStatus']);
    Route::post('order/payment-status', [Pelayan\OrderController::class, 'updatePaymentStatus']);
    Route::delete('order/delete', [Pelayan\OrderController::class, 'destroy']);
});

// Order Type
// 0 Takeaway
// 1 Dine-in

// Status order
// 0 Pending (setelah dibuat order oleh pelayan atau customer)
// 1 Accept (setelah diterima oleh pelayan atau dapur)
// 2 Process (setelah diterima pesanannya oleh dapur)
// 3 Finished (setelah pesanan selesai dibuat)

// Status Payment
// 0 Pending (Belum dibayar)
// 1 Finished (SUdah dibayar)
