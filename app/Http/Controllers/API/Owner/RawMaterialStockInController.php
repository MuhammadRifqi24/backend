<?php

namespace App\Http\Controllers\API\Owner;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

class RawMaterialStockInController extends Controller
{
    protected $rawMaterialStockInService;
    protected $cafeService;

    public function __construct(Services\Sultan\RawMaterialStockInService $rawMaterialStockInService, Services\CafeService $cafeService)
    {
        $this->rawMaterialStockInService = $rawMaterialStockInService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->rawMaterialStockInService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
