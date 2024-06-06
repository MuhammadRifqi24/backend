<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Facades\DB;

class CafeService
{
    public function getCafe($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Cafe";
            $status = true;
            switch ($ket) {
                case 'user_id':
                    $result = Models\Cafe::where('user_id', $id)->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'uuid':
                    $result = Models\Cafe::where('uuid', $id)->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'cafe_id':
                    $result = Models\Cafe::where('id', $id)->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'get_info':
                    $cafe_management = Models\CafeManagement::where('user_id', $id)->first();
                    if ($cafe_management) {
                        $cafe_id = $cafe_management->cafe_id;
                        $stan_id = $cafe_management->stan_id;
                    } else {
                        $status = false;
                        $cafe_id = null;
                        $stan_id = null;
                    }
                    $result = [
                        'cafe_id' => $cafe_id,
                        'stan_id' => $stan_id
                    ];
                    break;
                default:
                    $result = Models\Cafe::findOrFail($id);
                    break;
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

    public function getCafeManagement($cafeID)
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $result = Models\CafeManagement::with('cafe', 'user', 'userlevel')->where('cafe_id', $cafeID)->get();
            $message = 'Get Data Cafe Management';
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

    public function getListCafe()
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $result = Models\Cafe::get();
            $message = 'Get List Data Cafe';
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

    public function updateData($datas = [], $cafe_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $cafe = Models\Cafe::findOrFail($cafe_id);
            $cafe->name  = $datas['name'];
            $cafe->description = $datas['description'];
            $cafe->logo = $datas['logo'];
            $cafe->address = $datas['address'];
            $cafe->lat = $datas['lat'];
            $cafe->long = $datas['long'];
            $cafe->status = $datas['status'];
            $cafe->save();

            $result = $cafe;
            $message = "Successfully Update Cafe";
            $status = true;
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

    public function updateCafeStatus($datas = [], $cafe_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $cafe = Models\Cafe::findOrFail($cafe_id);
            $cafe->status = $datas['status'];
            $cafe->save();

            $result = $cafe;
            $message = "Successfully Update Cafe Status";
            $status = true;
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
