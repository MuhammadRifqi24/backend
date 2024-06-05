<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RawMaterialCategoryService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Raw Material Category";
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\RawMaterialCategory::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\RawMaterialCategory::where('uuid', $id)->first();
                    break;
                default:
                    $result = Models\RawMaterialCategory::findOrFail($id);
                    break;
            }

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

    public function getData()
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get All data Raw Material Category";
            $result = Models\RawMaterialCategory::all();

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

    public function insertData($datas = [])
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $uuid = Str::uuid()->getHex()->toString();

            $rawMaterialCategory = new Models\RawMaterialCategory();
            $rawMaterialCategory->name  = $datas['name'];
            $rawMaterialCategory->cafe_id  = $datas['cafe_id'];
            $rawMaterialCategory->description = $datas['description'];
            $rawMaterialCategory->status = $datas['status'];
            $rawMaterialCategory->uuid = $uuid;
            $rawMaterialCategory->save();

            $result = $rawMaterialCategory;
            $message = "Successfully insert Raw Material Category";
            $status = true;
            $code = 201;
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

    public function updateData($datas = [], $id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $result = Models\RawMaterialCategory::findOrFail($id);
            $result->name  = $datas['name'];
            $result->description = $datas['description'];
            $result->status = $datas['status'];
            $result->save();

            $message = "Successfully Update Raw Material Category";
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
            $category = Models\RawMaterialCategory::where('uuid', $uuid)->first();
            if ($category) {
                $category->delete();
                $status = true;
                $message = 'Successfully delete Raw Material Category';
            } else {
                $code = 404;
                $message = 'Data Raw Material Category tidak ditemukan';
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
}
