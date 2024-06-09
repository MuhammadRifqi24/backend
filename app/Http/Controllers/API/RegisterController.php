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

    public function registerUser(Requests\RegisterUserRequest $request): JsonResponse
    {
        $result = $this->userService->userRegister([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user'
        ], "customer");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function registerManager(Requests\RegisterManagerRequest $request): JsonResponse
    {
        $auth = auth()->user();
        $user = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($user['status'] == false) {
            return $this->errorResponse($user['result'], $user['message'], $user['code']);
        }

        $result = $this->userService->userRegister([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'manager',
            'cafe_id' => $user['result']['cafe_id'],
        ], "management");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function registerKasir(Requests\RegisterKasirRequest $request): JsonResponse
    {
        $auth = auth()->user();
        $user = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($user['status'] == false) {
            return $this->errorResponse($user['result'], $user['message'], $user['code']);
        }

        $result = $this->userService->userRegister([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'kasir',
            'cafe_id' => $user['result']['cafe_id'],
        ], "management");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function registerPelayan(Requests\RegisterPelayanRequest $request): JsonResponse
    {
        $auth = auth()->user();
        $user = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($user['status'] == false) {
            return $this->errorResponse($user['result'], $user['message'], $user['code']);
        }

        $result = $this->userService->userRegister([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'pelayan',
            'cafe_id' => $user['result']['cafe_id'],
        ], "management");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function registerDapur(Requests\RegisterDapurRequest $request): JsonResponse
    {
        $auth = auth()->user();
        $user = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($user['status'] == false) {
            return $this->errorResponse($user['result'], $user['message'], $user['code']);
        }

        $result = $this->userService->userRegister([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'dapur',
            'cafe_id' => $user['result']['cafe_id'],
        ], "management");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function registerStan(Requests\RegisterStanRequest $request): JsonResponse
    {
        $auth = auth()->user();
        $user = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($user['status'] == false) {
            return $this->errorResponse($user['result'], $user['message'], $user['code']);
        }

        $result = $this->userService->userRegister([
            'name' => $request->name,
            'name_stan' => $request->name_stan,
            'email' => $request->email,
            'role' => 'stan',
            'cafe_id' => $user['result']['cafe_id'],
        ], "management");

        return $this->successResponse($result, "testing");

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
