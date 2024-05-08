<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class ProductService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Product";
            $status = true;
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\Product::with('stock', 'category:id,name', 'stan:id,name,logo')->where('cafe_id', $cafeManagement->cafe_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\Product::with('stock', 'category:id,name')->where('uuid', $id)->first();
                    break;
                case 'stock':
                    $result = Models\Product::where(['uuid' => $id, 'is_stock' => true])->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Produk tidak memiliki Stok';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\Product::findOrFail($id);
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

            $product = new Models\Product();
            $product->cafe_id = $datas['cafe_id'];
            $product->stan_id = isset($datas['stan_id']) ? $datas['stan_id'] : null;
            $product->category_id = $datas['category_id'];
            $product->name  = $datas['name'];
            $product->image = $datas['image'];
            $product->description = $datas['description'];
            $product->harga_beli = $datas['harga_beli'];
            $product->harga_jual = $datas['harga_jual'];
            $product->is_stock = $datas['is_stock'];
            $product->status = $datas['status'];
            $product->uuid = $uuid;
            $product->save();

            $result = $product;
            $message = "Successfully insert Product";
            $status = true;
            $code = 201;

            if ($datas['is_stock'] == true || $datas['is_stock'] == 1) {
                $stockService = new StockService();
                $saveStock = $stockService->saveStock($product->id, $datas['qty'], "new", "Stock Awal");
                if ($saveStock['status'] === true) {
                    $result['stock'] = $saveStock['result'];
                } else {
                    DB::rollBack();
                    $code = 500;
                    $status = false;
                    $message = "Error Save Stock";
                }
            }

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
}