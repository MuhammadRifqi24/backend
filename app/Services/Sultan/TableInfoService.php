<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TableInfoService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Table Info";
            $status = true;
            switch ($ket) {
                case 'user_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\TableInfo::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['cafe_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\TableInfo::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\TableInfo::with('cafe')->where('uuid', $id)->first();
                    break;
                case 'status':
                    $result = Models\TableInfo::where(['uuid' => $id, 'status' => true])->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Meja tidak dapat dipesan';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\TableInfo::where('uuid', $id)->first();
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

    public function insertData($datas = [])
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $uuid = Str::uuid()->getHex()->toString();

            $table_info = new Models\TableInfo();
            $table_info->cafe_id = $datas['cafe_id'];
            $table_info->name  = $datas['name'];
            $table_info->status = $datas['status'];
            $table_info->uuid = $uuid;
            $table_info->save();

            $result = $table_info;
            $message = "Successfully insert Table Info";
            $status = true;
            $code = 201;

            if ($status === true) DB::commit();
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

    public function updateData($datas = [], $table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            $table_info->name  = $datas['name'];
            $table_info->status = $datas['status'];
            $table_info->save();

            $result = $table_info;
            $message = "Successfully Update Table Info";
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

    public function deleteData($uuid)
    {
        $status = false;
        $code = 200;
        $result = null;
        $message = '';
        try {
            $table_info = Models\TableInfo::where('uuid', $uuid)->first();
            if ($table_info) {
                $table_info->delete();
                $status = true;
                $result = true;
                $message = 'Successfully delete Table Info';
            } else {
                $code = 404;
                $message = 'Data tidak ditemukan';
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

    public function bookTable($datas = [], $table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            $table_info->status = $datas['status'];
            $table_info->save();

            $result = $table_info;
            $message = "Successfully Booking Table";
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

    public function finishTable($datas = [], $table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            $table_info->status = $datas['status'];
            $table_info->save();

            $result = $table_info;
            $message = "Successfully Booking Table";
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