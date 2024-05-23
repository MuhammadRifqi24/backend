<?php

namespace App\Http\Controllers\API\User;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class CallWaiterController extends Controller
{
    protected $callWaiterService;

    public function __construct(Services\Sultan\CallWaiterService $callWaiterService)
    {
        $this->callWaiterService = $callWaiterService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->callWaiterService->getDataByID($auth->id, 'user_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request, $uuid): JsonResponse
    {
        $result = $this->callWaiterService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\User\StoreCallWaiterRequest $request): JsonResponse
    {
        $result = $this->callWaiterService->insertData([
            'user_id' => $request['user_id'],
            'table_info_id' => $request['table_info_id'],
            'cafe_id' => $request['cafe_id'],
            'note' => $request->note,
            'status' => false,
        ]);
        echo "benar";

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        echo "1";
        
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function cancel(Requests\Pelayan\UpdateStatusCallWaiterRequest $request): JsonResponse
    {
        $checkData = $this->callWaiterService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->callWaiterService->updateStatus([
            'status' => 2,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function completed(Requests\Pelayan\UpdateStatusCallWaiterRequest $request): JsonResponse
    {
        $checkData = $this->callWaiterService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->callWaiterService->updateStatus([
            'status' => 1,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
