<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;

class RegisterController extends Controller
{
    protected $userService;
    protected $cafeService;
    public function __construct(Services\UserService $userService, Services\CafeService $cafeService)
    {
        $this->userService = $userService;
        $this->cafeService = $cafeService;
    }

    public function registerOwner(Requests\RegisterOwnerRequest $request): JsonResponse
    {
        // return $this->successResponse($request->all(), 'testing');
        $result = $this->userService->userRegister([
            'name' => $request->name,
            'name_cafe' => $request->name_cafe,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'owner'
        ], "management");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
