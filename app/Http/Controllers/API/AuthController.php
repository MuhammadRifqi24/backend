<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class AuthController extends Controller
{
    protected $userService;
    public function __construct(Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(): JsonResponse
    {
        $result = $this->userService->userProfile();
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function login(Requests\LoginRequest $request): JsonResponse
    {
        $result = $this->userService->userLogin($request);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(): JsonResponse
    {
        $result = $this->userService->userLogout();
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
