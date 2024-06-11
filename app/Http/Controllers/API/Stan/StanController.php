<?php

namespace App\Http\Controllers\API\Stan;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\Sultan\FileUploadService as FUS;

class StanController extends Controller
{
    protected $stanService;

    public function __construct(Services\Sultan\StanService $stanService) {
        $this->stanService = $stanService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $resultCafeManagement = $this->stanService->getStan($auth->id, 'get_info');
        if ($resultCafeManagement['status'] == false) {
            return $this->errorResponse($resultCafeManagement['result'], $resultCafeManagement['message'], $resultCafeManagement['code']);
        }
        
        $stan_id = $resultCafeManagement['result']['stan_id'];

        $result = $this->stanService->getStan($stan_id, 'stan_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    } 

    public function update(Requests\Stan\UpdateStanRequest $request): JsonResponse
    {
        $checkData = $this->stanService->getStan($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();
        $name_logo = $checkData->logo;
        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $name_logo = $checkData->id . '-stan-' . $auth->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $upload = FUS::uploadStan($file, $name_logo, $checkData->logo);
            if ($upload['status'] === false) {
                return $this->errorResponse($upload['result'], "Gagal Upload", 500);
            }
        }

        $result = $this->stanService->updateData([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $name_logo,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
