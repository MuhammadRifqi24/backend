<?php

namespace App\Http\Controllers\API\Owner;

use App\Services;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;

class StockController extends Controller
{
    protected $stockService;
    protected $productService;
    public function __construct(Services\Sultan\StockService $stockService, Services\Sultan\ProductService $productService)
    {
        $this->stockService = $stockService;
        $this->productService = $productService;
    }

    public function incrementData(Requests\Owner\StoreStockRequest $request, $uuid): JsonResponse
    {
        $product = $this->productService->getDataByID($uuid, 'stock');
        if ($product['status'] == false) {
            return $this->errorResponse($product['result'], $product['message'], $product['code']);
        }
        $product = $product['result'];

        $description = $request->description ? $request->description : "Stock Tambahan";
        $result = $this->stockService->saveStock($product->id, $request->qty, "in", $description);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function decrementData(Requests\Owner\StoreStockRequest $request, $uuid): JsonResponse
    {
        $product = $this->productService->getDataByID($uuid);
        if ($product['status'] == false) {
            return $this->errorResponse($product['result'], $product['message'], $product['code']);
        }
        $product = $product['result'];

        $description = $request->description ? $request->description : "Stock Berkurang";
        $result = $this->stockService->saveStock($product->id, $request->qty, "out", $description);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
