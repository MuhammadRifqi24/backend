<?php

namespace App\Services\Sultan;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryService
{

    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Category";
            switch ($ket) {
                case 'uuid':
                    $result = Category::where('uuid', $id)->first();
                    break;

                default:
                    $result = Category::findOrFail($id);
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
            $message = "Get All data Category";
            $result = Category::all();

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

            $product = new Category();
            $product->name  = $datas['name'];
            $product->description = $datas['description'];
            $product->status = $datas['status'];
            $product->uuid = $uuid;
            $product->save();

            $result = $product;
            $message = "Successfully insert Category";
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
            $result = Category::findOrFail($id);
            $result->name  = $datas['name'];
            $result->description = $datas['description'];
            $result->status = $datas['status'];
            $result->save();

            $message = "Successfully Update Category";
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
            $category = Category::where('uuid', $uuid)->first();
            if ($category) {
                $category->delete();
                $status = true;
                $message = 'Successfully delete Category';
            } else {
                $code = 404;
                $message = 'Data Category tidak ditemukan';
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
