<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Facades\Cache;
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
            $cacheCafeName = "cafe_{$ket}";
            $cacheCategoryName = "category_{$ket}";
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement =  Cache::tags(['get_list_category'])->remember($cacheCafeName, now()->addMinute(150), function () use ($id){
                        return Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    });
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Cache::tags(['get_list_category'])->remember($cacheCategoryName, now()->addMinute(150), function () use ($cafeManagement){
                            return Models\Category::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
                        });
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;

                case 'uuid':
                    $result = Cache::tags(['get_list_category'])->remember($cacheCategoryName, now()->addMinute(150), function () use ($id){
                        return Models\Category::where('uuid', $id)->first();
                    });
                    break;

                default:
                    $result = Cache::tags(['get_list_category'])->remember($cacheCategoryName, now()->addMinute(150), function () use ($id){
                        return Models\Category::findOrFail($id);
                    });
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
            $result = Cache::tags(['get_list_category'])->remember('list_category', now()->addMinute(150), function (){
                return Models\Category::all();
            });

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

            $product = new Models\Category();
            $product->cafe_id  = $datas['cafe_id'];
            $product->name  = $datas['name'];
            $product->description = $datas['description'];
            $product->status = $datas['status'];
            $product->uuid = $uuid;
            $product->save();

            $result = $product;
            $message = "Successfully insert Category";
            $status = true;
            $code = 201;

            Cache::tags('get_list_category')->flush();
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
            $result = Models\Category::findOrFail($id);
            $result->name  = $datas['name'];
            $result->description = $datas['description'];
            $result->status = $datas['status'];
            $result->save();

            $message = "Successfully Update Category";
            $status = true;
            Cache::tags('get_list_category')->flush();
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
            $category = Models\Category::where('uuid', $uuid)->first();
            if ($category) {
                $category->delete();
                $status = true;
                Cache::tags('get_list_category')->flush();
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
