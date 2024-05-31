<?php

namespace App\Http\Controllers\API\Manager;

use App\Services;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;

class RawMaterialStockController extends Controller
{
    protected $rawMaterialStockService;
    protected $rawMaterialService;
    public function __construct(Services\Sultan\RawMaterialStockService $rawMaterialStockService, Services\Sultan\RawMaterialService $rawMaterialService)
    {
        $this->rawMaterialStockService = $rawMaterialStockService;
        $this->rawMaterialService = $rawMaterialService;
    }

    public function incrementData(Requests\Manager\StoreRawMaterialStockRequest $request, $uuid): JsonResponse
    {
        $rawMaterial = $this->rawMaterialService->getDataByID($uuid, 'stock');
        if ($rawMaterial['status'] == false) {
            return $this->errorResponse($rawMaterial['result'], $rawMaterial['message'], $rawMaterial['code']);
        }
        $rawMaterial = $rawMaterial['result'];

        $description = $request->description ? $request->description : "Stock Raw Material Tambahan";
        $result = $this->rawMaterialStockService->saveRawMaterialStock($rawMaterial->id, $request->qty, "in", $description);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function decrementData(Requests\Manager\StoreRawMaterialStockRequest $request, $uuid): JsonResponse
    {
        $rawMaterial = $this->rawMaterialService->getDataByID($uuid);
        if ($rawMaterial['status'] == false) {
            return $this->errorResponse($rawMaterial['result'], $rawMaterial['message'], $rawMaterial['code']);
        }
        $rawMaterial = $rawMaterial['result'];

        $description = $request->description ? $request->description : "Stock Raw Material Berkurang";
        $result = $this->rawMaterialStockService->saveRawMaterialStock($rawMaterial->id, $request->qty, "out", $description);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
