<?php

namespace App\Http\Controllers\API\Pelayan;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class OrderController extends Controller
{
    protected $orderService;
    protected $cafeService;

    public function __construct(Services\Sultan\OrderService $orderService, Services\Sultan\CafeService $cafeService)
    {
        $this->orderService = $orderService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->orderService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request, $uuid): JsonResponse
    {
        $result = $this->orderService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUserId(Request $request, $user_id): JsonResponse
    {
        $result = $this->orderService->getDataByID($user_id, 'user_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByTableInfoId(Request $request, $table_info_id): JsonResponse
    {
        $result = $this->orderService->getDataByID($table_info_id, 'table_info_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
