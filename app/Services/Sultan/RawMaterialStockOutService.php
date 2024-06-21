<?php

namespace App\Services\Sultan;

use App\Models;

class RawMaterialStockOutService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Raw Material";
            $status = true;
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\RawMaterialStockOut::whereHas('raw_material_stock.raw_material', function ($query) use ($cafeManagement) {
                            $query->where('cafe_id', $cafeManagement->cafe_id);
                        })->with('raw_material_stock', 'raw_material_stock.raw_material', 'raw_material_stock.raw_material.raw_material_category:id,name', 'raw_material_stock.raw_material.stan:id,name,logo')->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'stan_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'stan_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\RawMaterialStockOut::whereHas('raw_material_stock.raw_material', function ($query) use ($cafeManagement) {
                            $query->where('stan_id', $cafeManagement->stan_id);
                        })->with('raw_material_stock', 'raw_material_stock.raw_material', 'raw_material_stock.raw_material.raw_material_category:id,name', 'raw_material_stock.raw_material.stan:id,name,logo')->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\RawMaterialStockOut::where('uuid', $id)->first();
                    break;
            }
        } catch (\Throwable $e) {
            $code = 500;
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
