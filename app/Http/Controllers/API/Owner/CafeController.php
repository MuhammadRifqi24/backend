<?php

namespace App\Http\Controllers\API\Owner;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\Sultan\FileUploadService as FUS;

class CafeController extends Controller
{
    protected $cafeService;

    public function __construct(Services\Sultan\CafeService $cafeService) {
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->cafeService->getCafe($auth->id, 'user_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Owner\UpdateCafeRequest $request): JsonResponse
    {
        $checkData = $this->cafeService->getCafe($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();
        $name_logo = $checkData->logo;
        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $name_logo = $checkData->cafe_id . '-cafe-' . $auth->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $upload = FUS::uploadCafe($file, $name_logo, $checkData->logo);
            if ($upload['status'] === false) {
                return $this->errorResponse($upload['result'], "Gagal Upload", 500);
            }
        }

        $result = $this->cafeService->updateData([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $name_logo,
            'address' => $request->address,
            'lat' => $request->lat,
            'long' => $request->long,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
