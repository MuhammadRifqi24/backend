<?php

namespace App\Http\Controllers\API\Owner;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

class RawMaterialStockOutController extends Controller
{
    protected $rawMaterialStockOutService;
    protected $cafeService;

    public function __construct(Services\Sultan\RawMaterialStockOutService $rawMaterialStockOutService, Services\CafeService $cafeService)
    {
        $this->rawMaterialStockOutService = $rawMaterialStockOutService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->rawMaterialStockOutService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
