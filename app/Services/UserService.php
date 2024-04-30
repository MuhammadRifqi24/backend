<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function userLogin($request)
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $getUser = User::with('user_level')->findOrFail($user->id);
                $result['token'] = $this->createToken($user);
                $result['user'] = $getUser;
                $message = 'Succesfully User Login';
                $status = true;
            } else {
                $message = 'Unauthorized...';
                $code = 401;
            }
        } catch (\Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $result = [
                'get_file' => $e->getFile(),
                'get_line' => $e->getLine()
            ];
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'result' => $result
        ];
    }

    public function userLogout()
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Logout Successfully";
            $user = Auth::user();
            $result = $this->deleteToken($user);
            $status = true;
        } catch (\Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $result = [
                'get_file' => $e->getFile(),
                'get_line' => $e->getLine()
            ];
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'result' => $result
        ];
    }

    private function createToken($user)
    {
        return $user->createToken('HanaCanKaliNyo3')->plainTextToken;
    }

    private function deleteToken($user)
    {
        return $user->tokens()->delete();
    }
}
