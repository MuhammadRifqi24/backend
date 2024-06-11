<?php

namespace App\Http\Controllers\API\Customer;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as Controller;
use App\Services\CafeService;
use App\Services\Rifqi\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $cafeService;

    public function __construct(ProductService $productService, CafeService $cafeService)
    {
        $this->productService = $productService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->productService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
