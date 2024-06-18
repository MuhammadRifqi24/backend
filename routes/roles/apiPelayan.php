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
    Route::get('table/index', [Pelayan\TableInfoController::class, 'index']);
    Route::post('table/insert', [Pelayan\TableInfoController::class, 'insert']);
    Route::post('table/update', [Pelayan\TableInfoController::class, 'update']);
    Route::post('table/status', [Pelayan\TableInfoController::class, 'updateStatus']);
    // Route::post('table/book-table', [Pelayan\TableInfoController::class, 'bookTable']);
    // Route::post('table/finish-table', [Pelayan\TableInfoController::class, 'finishTable']);
    Route::delete('table/delete', [Pelayan\TableInfoController::class, 'destroy']);

    Route::get('order/index', [Pelayan\OrderController::class, 'index']);
    Route::get('order/view/{uuid}', [Pelayan\OrderController::class, 'getByUUID']);
    Route::post('order/insert', [Pelayan\OrderController::class, 'insert']);
    Route::post('order/status', [Pelayan\OrderController::class, 'updateOrderStatus']);
    // Route::post('order/cancel', [Pelayan\OrderController::class, 'cancel']);

    // Route::get('order/uuid/{uuid}', [Pelayan\OrderController::class, 'getByUUID']);
    // Route::get('order/user/{user_id}', [Pelayan\OrderController::class, 'getByUserId']);
    // Route::get('order/table/{table_info_id}', [Pelayan\OrderController::class, 'getByTableInfoId']);
    // Route::post('order/update', [Pelayan\OrderController::class, 'update']);
    // Route::post('order/payment-status', [Pelayan\OrderController::class, 'updatePaymentStatus']);
    // Route::delete('order/delete', [Pelayan\OrderController::class, 'destroy']);

    Route::get('call-waiter/index', [Pelayan\CallWaiterController::class, 'index']);
    Route::get('call-waiter/uuid/{uuid}', [Pelayan\CallWaiterController::class, 'getByUUID']);
    Route::post('call-waiter/completed', [Pelayan\CallWaiterController::class, 'completed']);
    Route::post('call-waiter/cancel', [Pelayan\CallWaiterController::class, 'cancel']);

    Route::get('product/index', [Pelayan\ProductController::class, 'index']);
    Route::get('product/view/{uuid}', [Pelayan\ProductController::class, 'getByUUID']);
});

// Order Type
// 0 Takeaway
// 1 Dine-in

// Status order
// 0 Pending (setelah pesanan dibuat oleh pelayan atau customer)
// 1 Accept (pesanan diterima oleh pelayan atau dapur)
// 2 Process (pesanan diproses oleh dapur)
// 3 Finished (pesanan selesai dibuat)
// 4 Collected (pesanan sudah diambil oleh customer)
// 5 Canceled (pesanan dibatalkan)

// Status Payment
// 0 Pending (Belum dibayar)
// 1 Finished (SUdah dibayar)

// Status Call Waiter
// 0 Pending
// 1 Completed
// 2 Cancel