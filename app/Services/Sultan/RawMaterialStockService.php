<?php

namespace App\Services\Sultan;

use App\Models;

class RawMaterialStockService
{
    public function saveRawMaterialStock($raw_material_id, $qty, $info, $description = "") // info => new, in, out
    {
        $status = false;
        $code = 200;
        $result = null;
        $message = "";
        try {
            $check = Models\RawMaterialStock::where('raw_material_id', $raw_material_id);
            if ($check->count() > 0) {
                $rawMaterialStock = $check->first();
            } else {
                $rawMaterialStock = new Models\RawMaterialStock();
                $rawMaterialStock->raw_material_id = $raw_material_id;
                $rawMaterialStock->qty = $qty;
                $rawMaterialStock->date = now();
                $rawMaterialStock->save();
            }

            switch ($info) {
                case 'new':
                    $rawMaterialStockIn = new Models\RawMaterialStockIn();
                    $rawMaterialStockIn->raw_material_stock_id = $rawMaterialStock->id;
                    $rawMaterialStockIn->qty = $qty;
                    $rawMaterialStockIn->description = $description;
                    $rawMaterialStockIn->date = now();
                    $rawMaterialStockIn->save();

                    $message = "Successfully insert Raw Material Stock";
                    break;

                case 'in':
                    $rawMaterialStock->increment('qty', $qty);
                    $rawMaterialStock->date = now();
                    $rawMaterialStock->save();

                    $rawMaterialStockIn = new Models\RawMaterialStockIn();
                    $rawMaterialStockIn->raw_material_stock_id = $rawMaterialStock->id;
                    $rawMaterialStockIn->qty = $qty;
                    $rawMaterialStockIn->description = $description;
                    $rawMaterialStockIn->date = now();
                    $rawMaterialStockIn->save();
                    $message = "Successfully increment Raw Material Stock";
                    break;
                case 'out':
                    $rawMaterialStock->decrement('qty', $qty);
                    $rawMaterialStock->date = now();
                    $rawMaterialStock->save();

                    $rawMaterialStockOut = new Models\RawMaterialStockOut();
                    $rawMaterialStockOut->raw_material_stock_id = $rawMaterialStock->id;
                    $rawMaterialStockOut->qty = $qty;
                    $rawMaterialStockOut->description = $description;
                    $rawMaterialStockOut->date = now();
                    $rawMaterialStockOut->save();
                    $message = "Successfully decrement Raw Material Stock";
                    break;
            }

            $status = true;
            $result = $rawMaterialStock;
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
