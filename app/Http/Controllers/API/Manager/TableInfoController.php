<?php

namespace App\Http\Controllers\API\Manager;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

class TableInfoController extends Controller
{
    protected $tableInfoService;
    
    public function __construct(Services\Sultan\TableInfoService $tableInfoService)
    {
        $this->tableInfoService = $tableInfoService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->tableInfoService->getDataByID($auth->id, 'user_id');
        
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Manager\StoreTableInfoRequest $request): JsonResponse
    {
        $auth = $request->user();

        //* check cafe_management
        $cafe_management = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($cafe_management['status'] == false) {
            return $this->errorResponse($cafe_management['result'], $cafe_management['message'], $cafe_management['code']);
        }
        $cafe_management = $cafe_management['result'];

        $result = $this->tableInfoService->insertData([
            'cafe_id' => $cafe_management['cafe_id'],
            'name' => $request->name,
            'status' => true
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Manager\UpdateTableInfoRequest $request): JsonResponse
    {
        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();

        $result = $this->tableInfoService->updateData([
            'name' => $request->name,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(Requests\Manager\DeleteTableInfoRequest $request): JsonResponse
    {
        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }

        $result = $this->tableInfoService->deleteData($request->uuid);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function bookTable(Requests\Manager\BookTableInfoRequest $request): JsonResponse
    {
        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();

        $result = $this->tableInfoService->bookTable([
            'name' => $request->name,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function finishTable(Requests\Manager\BookTableInfoRequest $request): JsonResponse
    {
        $checkData = $this->tableInfoService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();

        $result = $this->tableInfoService->finishTable([
            'name' => $request->name,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
