<?php

namespace App\Services;

use App\Models;

class StockService
{
    public function saveStock($product_id, $qty, $info, $description = "") // info => new, in, out
    {
        $status = false;
        $code = 200;
        $result = null;
        $message = "";
        try {
            $check = Models\Stock::where('product_id', $product_id);
            if ($check->count() > 0) {
                $stock = Models\Stock::findOrFail($check->first()->id);
            } else {
                $stock = new Models\Stock();
                $stock->product_id = $product_id;
                $stock->qty = $qty;
                $stock->date = now();
                $stock->save();
            }

            switch ($info) {
                case 'new':
                    $stockIn = new Models\StockIn();
                    $stockIn->stock_id = $stock->id;
                    $stockIn->qty = $qty;
                    $stockIn->description = $description;
                    $stockIn->date = now();
                    $stockIn->save();

                    $message = "Successfully insert Stock";
                    break;

                case 'in':
                    $stock->increment('qty', $qty);
                    $stock->date = now();
                    $stock->save();

                    $stockIn = new Models\StockIn();
                    $stockIn->stock_id = $stock->id;
                    $stockIn->qty = $qty;
                    $stockIn->description = $description;
                    $stockIn->date = now();
                    $stockIn->save();
                    $message = "Successfully increment Stock";
                    break;
                case 'out':
                    $stock->decrement('qty', $qty);
                    $stock->date = now();
                    $stock->save();

                    $stockOut = new Models\StockOut();
                    $stockOut->stock_id = $stock->id;
                    $stockOut->qty = $qty;
                    $stockOut->description = $description;
                    $stockOut->date = now();
                    $stockOut->save();
                    $message = "Successfully decrement Stock";
                    break;
            }

            $status = true;
            $result = $stock;
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
