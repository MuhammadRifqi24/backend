<?php

namespace App\Http\Controllers\API;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(Services\Sultan\CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->categoryService->getData();
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function find($uuid): JsonResponse
    {
        $result = $this->categoryService->getDataByID($uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Stan\StoreCategoryRequest $request): JsonResponse
    {
        $result = $this->categoryService->insertData([
            'name' => $request->name,
            'description' => $request->description,
            'status' => true
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Stan\UpdateCategoryRequest $request): JsonResponse
    {
        $checkData = $this->categoryService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $result = $this->categoryService->updateData([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(Requests\Stan\DeleteCategoryRequest $request): JsonResponse
    {
        $checkData = $this->categoryService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }

        $result = $this->categoryService->deleteData($request->uuid);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
