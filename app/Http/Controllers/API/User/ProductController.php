<?php

namespace App\Http\Controllers\API\User;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;

class ProductController extends Controller
{
    protected $productService;
    protected $cafeService;
    public function __construct(Services\Sultan\ProductService $productService, Services\CafeService $cafeService)
    {
        $this->productService = $productService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->productService->getDataByID($request->id, 'id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function getByUUID(Request $request): JsonResponse
    {
        $result = $this->productService->getDataByID($request->uuid, 'uuid');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}