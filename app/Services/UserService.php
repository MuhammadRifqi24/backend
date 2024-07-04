<?php

namespace App\Services;

use App\Models;
use App\Models\UserLevel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications;
use Illuminate\Support\Facades\Notification;

class UserService
{
    public function userProfile()
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Profile";
            $auth = Auth::user();
            $result = Models\User::with('user_level')->findOrFail($auth->id);
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

    public function getUserByID($id, $ket = '', $datas = [])
    {
        $status = true;
        $code = 200;
        $result = null;
        try {
            switch ($ket) {
                case 'uuid':
                    $message = "Get User By UUID";
                    $result = Models\User::where('uuid', $id)->first();
                    break;

                case 'verified':
                    $message = "Email dapat di verifikasi dan segera ganti Password Anda, terimakasih";
                    $result = Models\User::where(['uuid' => $id, 'email' => $datas['email']])->first();
                    if ($result) {
                        if ($result->is_active == true) {
                            $status = false;
                            $message = "Email Sudah Aktif";
                        }
                    } else {
                        $status = false;
                        $code = 404;
                        $message = 'Data Email tidak Ditemukan...';
                    }
                    break;

                default:
                    $result = Models\User::findOrFail($id);
                    $message = "Get User By ID";
                    break;
            }
        } catch (\Throwable $e) {
            $status = false;
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

    public function userVerifyEmail($request)
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            // Check Data
            $user = Models\User::where([
                "email" => $request->email,
                "uuid" => $request->uuid,
                "email_verified_at" => null,
            ]);

            $check = $user->first();
            if ($check) {
                $update = [
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'is_active' => true
                ];
                if ($request->password) $update += ['password' => bcrypt($request->password)];
                $user->update($update);

                Models\Cafe::where('user_id', $check->id)->update(['status' => true]);
                Models\CafeManagement::where('user_id', $check->id)->update(['status' => true]);
                $status = true;
                $result = true;
                $message = 'Successfully Verify Email User';
            } else {
                $code = 404;
                $message = 'Data Email tidak Ditemukan...';
            }
        } catch (\Throwable $e) {
            DB::rollBack();
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

    public function userLogin($request)
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $getUser = Models\User::with('user_level')->findOrFail($user->id);
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

    public function userRegister($datas, $level)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $uuid = Str::uuid()->getHex()->toString();
            $password = isset($datas['password']) ? $datas['password'] : "password";

            $checkUser = Models\User::where('email', $datas['email'])->first();
            if ($checkUser) {
                $user = $checkUser;
            } else {
                //* Save Data User
                $user = new Models\User();
                $user->name = $datas['name'];
                $user->email = $datas['email'];
                $user->password = bcrypt($password);
                //! $user->email_verified_at = now();
                $user->uuid = $uuid;
                $user->save();
            }

            $cafeID = isset($datas['cafe_id']) ? $datas['cafe_id'] : null;
            $stanID = isset($datas['stan_id']) ? $datas['stan_id'] : null;

            //* Save Data UserLevel
            $userLevel = Models\UserLevel::where(['user_id' => $user->id, 'level' => $level, 'role' => $datas['role']])->first();
            if (!$userLevel) {
                $userLevel = new Models\UserLevel();
                $userLevel->user_id = $user->id;
                $userLevel->level = $level;
                $userLevel->role = $datas['role'];
                $userLevel->save();
            }

            // $url_ = env('URL_FE', 'https://cafe.markazvirtual.com');
            $url_ = env('URL_FE', 'http://localhost:3006');
            $url = $url_ . '/verify?uuid=' . $uuid . '&email=' . $user->email;

            switch ($datas['role']) {
                case 'owner':
                    //* Save Data Cafe
                    $cafe = new Models\Cafe();
                    $cafe->user_id = $user->id;
                    $cafe->name = $datas['name_cafe'];
                    $cafe->uuid = $uuid;
                    $cafe->save();

                    $url = $url_ . '/verify/owner?uuid=' . $uuid . '&email=' . $user->email;
                    $cafeID = $cafe->id;
                    // Notification::send($user, new Notifications\UserVerifyEmail($user, ['url' => $url]));
                    break;
                case 'stan':
                    //* Save Data Stan
                    $stan = new Models\Stan();
                    $stan->user_id = $user->id;
                    $stan->cafe_id = $cafeID;
                    $stan->name = $datas['name_stan'];
                    $stan->uuid = $uuid;
                    $stan->save();

                    $stanID = $stan->id;
                    $message = ' Pemilik Stan ' . $datas['name_stan'];
                    // Notification::send($user, new Notifications\OwnerSendEmail($user, ['message' => $message, 'url' => $url]));
                    break;
                case 'manager':
                    $message = ' Manager';
                    // Notification::send($user, new Notifications\OwnerSendEmail($user, ['message' => $message, 'url' => $url]));
                    break;
                case 'kasir':
                    $message = ' Kasir';
                    // Notification::send($user, new Notifications\OwnerSendEmail($user, ['message' => $message, 'url' => $url]));
                    break;
                case 'pelayan':
                    $message = ' Pelayan';
                    Notification::send($user, new Notifications\OwnerSendEmail($user, ['message' => $message, 'url' => $url]));
                    break;
                case 'user':
                    $message = ' User';
                    // Notification::send($user, new Notifications\OwnerSendEmail($user, ['message' => $message, 'url' => $url]));
                    break;
            }

            if ($level === "management") {
                //* Save Data CafeManagement
                $cafeManagement = Models\CafeManagement::where(['cafe_id' => $cafeID, 'user_id' => $user->id, 'userlevel_id' => $userLevel->id])->first();
                if (!$cafeManagement) {
                    $cafeManagement = new Models\CafeManagement();
                    $cafeManagement->cafe_id = $cafeID;
                    $cafeManagement->stan_id = $stanID;
                    $cafeManagement->user_id = $user->id;
                    $cafeManagement->userlevel_id = $userLevel->id;
                    $cafeManagement->save();
                }
            }

            $result = $user;
            $code = 201;

            $status = true;
            $message = 'Successfully Register User';
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
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
}
