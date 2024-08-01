<?php

namespace App\Http\Controllers\API\Kasir;

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
    private function getOrderDetailServiceById($result){
        $orderDetails = $this->orderDetailService->getDataByID($result['id'], 'order_id');
        if($orderDetails['status']) {
            $result['orderDetails'] = $orderDetails['result'];
        }
        
        return $result;
    }

    // mengambil semua data order detail service
    // parameter yg dikirim berupa array yg berisi id
    private function getOrderDetailServiceByIds($result){
        foreach ($result as $key => $value) {
            $orderDetails = $this->orderDetailService->getDataByID($value['id'], 'order_id');
            if($orderDetails['status']) {
                $result[$key]['orderDetails'] = $orderDetails['result'];
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
            $result['result'] = $this->getOrderDetailServiceByIds($result['result']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request, $uuid): JsonResponse
    {
        $result = $this->orderService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        } else {
            $result['result'] = $this->getOrderDetailServiceById($result['result']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function updateOrderStatus(Requests\Kasir\UpdateStatusOrderRequest $request): JsonResponse
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

    public function updatePaymentStatus(Requests\Kasir\UpdateStatusOrderPaymentRequest $request): JsonResponse
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
}
