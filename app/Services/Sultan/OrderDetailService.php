<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class OrderDetailService
{
    public function getDataByID($id, $ket = "", $datas = null)
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data OrderDetail";
            $status = true;
            switch ($ket) {
                case 'order_id':
                    $result = Models\OrderDetail::with('stan', 'product')->where('order_id', $id)->get();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'stan_id':
                    $result = Models\OrderDetail::with('stan', 'product')->where(['order_id' => $id, 'stan_id' => $datas['stan_id']])->get();
                    if($result->isEmpty()) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\OrderDetail::with('order', 'stan')->where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\OrderDetail::where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
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