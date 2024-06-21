<?php

namespace App\Http\Controllers\API\User;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class CafeController extends Controller
{
    protected $cafeService;

    public function __construct(Services\Sultan\CafeService $cafeService) {
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->cafeService->getListCafe();
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request): JsonResponse
    {
        $result = $this->cafeService->getCafe($request->uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function updateCafeStatus(Requests\Admin\UpdateCafeStatusRequest $request): JsonResponse
    {
        $checkData = $this->cafeService->getCafe($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->cafeService->updateCafeStatus([
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
