<?php

namespace App\Http\Controllers\API\Stan;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

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
        $auth = $request->user();
        $result = $this->productService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
