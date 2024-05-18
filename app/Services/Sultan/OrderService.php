<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class OrderService
{
    private $orderDetailService;
    
    public function __construct(OrderDetailService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }

    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Order";
            $status = true;
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\Order::with('table_info', 'user', 'cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }

                    if($result) {
                        foreach ($result as $key => $value) {
                            $orderDetails = $this->orderDetailService->getDataByID($value['id'], 'order_id');
                            if($orderDetails['status']) {
                                $result[$key]['orderDetails'] = $orderDetails['result'];
                            }
                        }
                    }
                    break;
                case 'user_id':
                    $result = Models\Order::with('table_info', 'user', 'cafe')->where('user_id', $id)->get();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    } else {
                        foreach ($result as $key => $value) {
                            $orderDetails = $this->orderDetailService->getDataByID($value['id'], 'order_id');
                            if($orderDetails['status']) {
                                $result[$key]['orderDetails'] = $orderDetails['result'];
                            }
                        }
                    }
                    break;
                case 'table_info_id':
                    $result = Models\Order::with('table_info', 'user', 'cafe')->where('table_info_id', $id)->get();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    } else {
                        foreach ($result as $key => $value) {
                            $orderDetails = $this->orderDetailService->getDataByID($value['id'], 'order_id');
                            if($orderDetails['status']) {
                                $result[$key]['orderDetails'] = $orderDetails['result'];
                            }
                        }
                    }
                    break;
                case 'uuid':
                    $result = Models\Order::with('table_info', 'user', 'cafe')->where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    } else {
                        $orderDetails = $this->orderDetailService->getDataByID($result['id'], 'order_id');
                        if($orderDetails['status']) {
                            $result['orderDetails'] = $orderDetails['result'];
                        }
                    }
                    break;
                default:
                    $result = Models\Order::where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    } else {
                        $orderDetails = $this->orderDetailService->getDataByID($result['id'], 'order_id');
                        if($orderDetails['status']) {
                            $result['orderDetails'] = $orderDetails['result'];
                        }
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