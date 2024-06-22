<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RawMaterialService
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
                        $result = Models\RawMaterial::with('raw_material_stock', 'raw_material_stock.stock_ins', 'raw_material_stock.stock_outs', 'raw_material_category:id,name', 'stan:id,name,logo')->where('cafe_id', $cafeManagement->cafe_id)->get();
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
                        $result = Models\RawMaterial::with('raw_material_stock', 'raw_material_category:id,name', 'stan:id,name,logo')->where('stan_id', $cafeManagement->stan_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\RawMaterial::with('raw_material_stock', 'raw_material_stock.stock_ins', 'raw_material_stock.stock_outs', 'raw_material_category:id,name')->where('uuid', $id)->first();
                    break;
                case 'stock':
                    $result = Models\RawMaterial::where(['uuid' => $id, 'is_stock' => true])->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Bahan Baku tidak memiliki Stok';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\RawMaterial::where('uuid', $id)->first();
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

            $rawMaterial = new Models\RawMaterial();
            $rawMaterial->cafe_id = $datas['cafe_id'];
            $rawMaterial->stan_id = isset($datas['stan_id']) ? $datas['stan_id'] : null;
            $rawMaterial->raw_material_category_id = $datas['raw_material_category_id'];
            $rawMaterial->name  = $datas['name'];
            $rawMaterial->image = $datas['image'];
            $rawMaterial->description = $datas['description'];
            $rawMaterial->harga_beli = $datas['harga_beli'];
            $rawMaterial->harga_jual = $datas['harga_jual'];
            $rawMaterial->is_stock = $datas['is_stock'];
            $rawMaterial->status = $datas['status'];
            $rawMaterial->uuid = $uuid;
            $rawMaterial->save();

            $result = $rawMaterial;
            $message = "Successfully insert Raw Material";
            $status = true;
            $code = 201;

            if ($datas['is_stock'] == true || $datas['is_stock'] == 1) {
                $rawMaterialStockService = new RawMaterialStockService();
                $saveRawMaterialStock = $rawMaterialStockService->saveRawMaterialStock($rawMaterial->id, $datas['qty'], "new", "Stock Awal");
                if ($saveRawMaterialStock['status'] === true) {
                    $result['stock'] = $saveRawMaterialStock['result'];
                } else {
                    DB::rollBack();
                    $code = 500;
                    $status = false;
                    $message = "Error Save Raw Material Stock";
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

    public function updateData($datas = [], $raw_material_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $rawMaterial = Models\RawMaterial::findOrFail($raw_material_id);
            $rawMaterial->raw_material_category_id = $datas['raw_material_category_id'];
            $rawMaterial->name  = $datas['name'];
            $rawMaterial->image = $datas['image'];
            $rawMaterial->description = $datas['description'];
            $rawMaterial->harga_beli = $datas['harga_beli'];
            $rawMaterial->harga_jual = $datas['harga_jual'];
            $rawMaterial->status = $datas['status'];
            $rawMaterial->save();

            $result = $rawMaterial;
            $message = "Successfully Update Raw Material";
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
            $rawMaterial = Models\RawMaterial::where('uuid', $uuid)->first();
            if ($rawMaterial) {
                $cekimage = public_path('/images/rawmaterial/' . $rawMaterial->image);
                if (file_exists($cekimage)) unlink($cekimage);
                $cekimage2 = public_path('/images/rawmaterial/thumbnail/' . $rawMaterial->image);
                if (file_exists($cekimage2)) unlink($cekimage2);
                $rawMaterial->delete();
                $status = true;
                $result = true;
                $message = 'Successfully delete Raw Material';
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
}