<?php

namespace App\Services\Sultan;

use App\Models;

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
                    echo $cafeManagement;
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
}