<?php

namespace App\Http\Controllers\API\Manager;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $cafeService;

    public function __construct(Services\Sultan\CategoryService $categoryService, Services\Sultan\CafeService $cafeService)
    {
        $this->categoryService = $categoryService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->categoryService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Manager\StoreCategoryRequest $request): JsonResponse
    {
        $auth = $request->user();
        //* check cafe_management
        $cafe_management = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($cafe_management['status'] == false) {
            return $this->errorResponse($cafe_management['result'], $cafe_management['message'], $cafe_management['code']);
        }
        $cafe_management = $cafe_management['result'];
        
        $result = $this->categoryService->insertData([
            'cafe_id' => $cafe_management['cafe_id'],
            'name' => $request->name,
            'description' => $request->description,
            'status' => true
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Manager\UpdateCategoryRequest $request): JsonResponse
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

    public function destroy(Request $request): JsonResponse
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

    public function getByUUID(Request $request): JsonResponse
    {
        $result = $this->categoryService->getDataByID($request->uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
