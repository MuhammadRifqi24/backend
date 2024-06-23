<?php

namespace App\Http\Controllers\API\User;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class TableInfoController extends Controller
{
    protected $tableInfoService;
    protected $cafeService;
    
    public function __construct(Services\Sultan\TableInfoService $tableInfoService, Services\Sultan\CafeService $cafeService)
    {
        $this->tableInfoService = $tableInfoService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->tableInfoService->getDataByID($request->id, 'cafe_id');
        
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function bookTable(Requests\User\BookTableInfoRequest $request): JsonResponse
    {
        $auth = $request->user();
        $hasBookedTable = $this->tableInfoService->getDataByID($auth->id, 'user_id');
        if ($hasBookedTable['status'] == true && $hasBookedTable['result']->status == 0) {
            $message = "Can't book more than 1 table";
            $code = 409;
            return $this->errorResponse([], $message, $code);
        }

        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->tableInfoService->bookTable([
            'user_id' => $request->user_id,
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function finishTable(Requests\User\FinishTableInfoRequest $request): JsonResponse
    {
        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();
        if($checkData['user_id'] != $auth->id) {
            $code = 404;
            $message = 'Data Not Found';
            return $this->errorResponse([], $message, $code);
        }

        $result = $this->tableInfoService->finishTable($checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
