<?php

namespace App\Http\Controllers\API\Owner;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class RawMaterialCategoryController extends Controller
{
    protected $rawMaterialCategoryService;

    public function __construct(Services\Sultan\RawMaterialCategoryService $rawMaterialCategoryService)
    {
        $this->rawMaterialCategoryService = $rawMaterialCategoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->rawMaterialCategoryService->getData();
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function find($uuid): JsonResponse
    {
        $result = $this->rawMaterialCategoryService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Owner\StoreRawMaterialCategoryRequest $request): JsonResponse
    {
        $result = $this->rawMaterialCategoryService->insertData([
            'name' => $request->name,
            'description' => $request->description,
            'status' => true
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Owner\UpdateRawMaterialCategoryRequest $request): JsonResponse
    {
        $checkData = $this->rawMaterialCategoryService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->rawMaterialCategoryService->updateData([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(Requests\Owner\DeleteRawMaterialCategoryRequest $request): JsonResponse
    {
        $checkData = $this->rawMaterialCategoryService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }

        $result = $this->rawMaterialCategoryService->deleteData($request->uuid);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
