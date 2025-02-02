<?php

namespace App\Http\Controllers\API\Stan;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderDetailService;
    protected $cafeService;

    public function __construct(Services\Sultan\OrderService $orderService, Services\Sultan\CafeService $cafeService, Services\Sultan\OrderDetailService $orderDetailService)
    {
        $this->orderService = $orderService;
        $this->orderDetailService = $orderDetailService;
        $this->cafeService = $cafeService;
    }

    // mengambil semua data order detail service
    // parameter yg dikirim berupa satu id
    private function getOrderDetailServiceById($result, $stan_id = null){
        $orderDetails = $this->orderDetailService->getDataByID($result['id'], 'stan_id', [
            'stan_id' => $stan_id
        ]);
        if($orderDetails['status']) {
            $result['orderDetails'] = $orderDetails['result'];
        }

        return $result;
    }

    // mengambil semua data order detail service
    // parameter yg dikirim berupa array yg berisi id
    private function getOrderDetailServiceByIds($result, $stan_id = null){
        foreach ($result as $key => $value) {
            $orderDetails = $this->orderDetailService->getDataByID($value['id'], 'stan_id', [
                'stan_id' => $stan_id
            ]);
            // echo $orderDetails['result'];
            if($orderDetails['status']) {
                $result[$key]['orderDetails'] = $orderDetails['result'];
            }
            if($orderDetails['status'] == false) {
                unset($result[$key]);
            }
        }

        return $result;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->orderService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        } else {
            $resultStan = $this->cafeService->getCafe($auth->id, 'get_info');
            $result['result'] = $this->getOrderDetailServiceByIds($result['result'], $resultStan['result']['stan_id']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request, $uuid): JsonResponse
    {
        $result = $this->orderService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        } else {
            $auth = $request->user();
            $resultStan = $this->cafeService->getCafe($auth->id, 'get_info');
            $result['result'] = $this->getOrderDetailServiceById($result['result'], $resultStan['result']['stan_id']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUserId(Request $request, $user_id): JsonResponse
    {
        $result = $this->orderService->getDataByID($user_id, 'user_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        } else {
            $result['result'] = $this->getOrderDetailServiceById($result['result']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByTableInfoId(Request $request, $table_info_id): JsonResponse
    {
        $auth = $request->user();
        $result = $this->orderService->getDataByID([
            'table_info_id' => $table_info_id,
            'user_id' => $auth->id,
        ], 'table_info_id');

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        } else {
            $result['result'] = $this->getOrderDetailServiceByIds($result['result']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Pelayan\StoreOrderRequest $request): JsonResponse
    {
        $result = $this->orderService->insertData([
            'user_id' => $request['user_id'],
            'table_info_id' => $request['table_info_id'],
            'cafe_id' => $request['cafe_id'],
            'customer_name' => $request->customer_name,
            'order_type' => $request->order_type,
            'note' => $request->note,
            'total_price' => $request->total_price,
            'status' => false,
            'payment_status' => false,
            'order_details' => $request['order_details'],
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Pelayan\UpdateOrderRequest $request): JsonResponse
    {
        $checkData = $this->orderService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->orderService->updateData([
            'table_info_id' => $request->table_info_id,
            'user_id' => $request->user_id,
            'customer_name' => $request->customer_name != null ? $request->customer_name : $checkData->customer_name,
            'note' => $request->note,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(Requests\Pelayan\DeleteOrderRequest $request): JsonResponse
    {
        $checkData = $this->orderService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }

        $result = $this->orderService->deleteData($request->uuid);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function updateOrderStatus(Requests\Pelayan\UpdateStatusOrderRequest $request): JsonResponse
    {
        $checkData = $this->orderService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->orderService->updateOrderStatus([
            'status' => $request->status,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function updatePaymentStatus(Requests\Pelayan\UpdateStatusOrderPaymentRequest $request): JsonResponse
    {
        $checkData = $this->orderService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->orderService->updatePaymentStatus([
            'payment_status' => $request->payment_status,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function cancel(Requests\Pelayan\CancelOrderRequest $request): JsonResponse
    {
        $checkData = $this->orderService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->orderService->updateOrderStatus([
            'status' => 5,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
