<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User;

// * {BASE_URL}/api/v1/pelayan
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Selamat Datang User'
    ]);
});

Route::middleware(['auth:sanctum', 'checkVerifyEmail', 'checkRole:user'])->group(function () {
    Route::get('table/index/{id}', [User\TableInfoController::class, 'index']);
    Route::post('table/book-table', [User\TableInfoController::class, 'bookTable']);
    Route::post('table/finish-table', [User\TableInfoController::class, 'finishTable']);

    Route::get('order/index', [User\OrderController::class, 'index']);
    Route::get('order/view/{uuid}', [User\OrderController::class, 'getByUUID']);
    Route::post('order/insert', [User\OrderController::class, 'insert']);
    Route::post('order/status', [User\OrderController::class, 'updateOrderStatus']);

    // Route::get('call-waiter/index', [User\CallWaiterController::class, 'index']);
    // Route::get('call-waiter/uuid/{uuid}', [User\CallWaiterController::class, 'getByUUID']);
    // Route::post('call-waiter/insert', [User\CallWaiterController::class, 'insert']);
    // Route::post('call-waiter/completed', [User\CallWaiterController::class, 'completed']);
    // Route::post('call-waiter/cancel', [User\CallWaiterController::class, 'cancel']);


    Route::get('cafe/index', [User\CafeController::class, 'index']);
    Route::get('cafe/view/{uuid}', [User\CafeController::class, 'getByUUID']);

    Route::get('product/index/{id}', [User\ProductController::class, 'index']);
    Route::get('product/view/{uuid}', [User\ProductController::class, 'getByUUID']);
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
// 1 Finished
// 2 Cancel