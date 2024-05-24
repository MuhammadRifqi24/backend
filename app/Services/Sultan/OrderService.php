<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class OrderService
{
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
                        if(!$result) {
                            $code = 404;
                            $message = 'Data Not Found';
                            $status = false;
                        }
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'user_id':
                    $result = Models\Order::with('table_info', 'user', 'cafe')->where('user_id', $id)->get();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'table_info_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id['user_id']])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\Order::with('table_info', 'user', 'cafe')
                                ->where([
                                    'cafe_id' => $cafeManagement->cafe_id,
                                    'table_info_id' => $id['table_info_id']
                                ])
                                ->get();
                        if(!$result) {
                            $code = 404;
                            $message = 'Data Not Found';
                            $status = false;
                        }
                    } else {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'uuid':
                    $result = Models\Order::with('table_info', 'user', 'cafe')->where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\Order::where('uuid', $id)->first();
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

    public function insertData($datas = [])
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $uuid = Str::uuid()->getHex()->toString();

            $order = new Models\Order();
            $order->user_id = isset($datas['user_id']) ? $datas['user_id'] : null;
            $order->table_info_id = isset($datas['table_info_id']) ? $datas['table_info_id'] : null;
            $order->cafe_id = $datas['cafe_id'];
            $order->customer_name = $datas['customer_name'];
            $order->order_type = $datas['order_type'];
            $order->note = isset($datas['note']) ? $datas['note'] : null;
            $order->total_price = $datas['total_price'];
            $order->status = $datas['status'];
            $order->payment_status = $datas['payment_status'];
            $order->uuid = $uuid;
            $order->save();

            $result = $order;

            // insert ke tabel orderdetail
            foreach ($datas['order_details'] as $key => $value) {
                $uuid = Str::uuid()->getHex()->toString();

                $order_detail = new Models\OrderDetail();
                $order_detail->order_id = $result->id;
                $order_detail->stan_id = isset($value['stan_id']) ? $value['stan_id'] : null;
                $order_detail->product_id = $value['product_id'];
                $order_detail->qty = $value['qty'];
                $order_detail->qty = $value['qty'];
                $order_detail->price = $value['price'];
                $order_detail->uuid = $uuid;
                $order_detail->save();

                $description = "Stock Berkurang";
                $stockService = new StockService();
                $resultStock = $stockService->saveStock($value['product_id'], $value['qty'], "out", $description);
                if($resultStock['status'] == false) {
                    $status = false;
                    break;
                } else {
                    $message = "Successfully insert Order and Order Detail";
                    $status = true;
                    $code = 201;
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

    public function updateData($datas = [], $order_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $order = Models\Order::findOrFail($order_id);
            $order->user_id = $datas['user_id'] == null ? null : $datas['user_id'];
            $order->table_info_id = $datas['table_info_id'] == null ? null : $datas['table_info_id'];
            $order->customer_name = $datas['customer_name'];
            $order->note = $datas['note'];
            $order->save();

            $result = $order;
            $message = "Successfully Update Order";
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
            $order = Models\Order::where('uuid', $uuid)->first();
            if ($order) {
                $order->delete();
                $status = true;
                $result = true;
                $message = 'Successfully delete Order';
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

    public function updateOrderStatus($datas = [], $order_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $order = Models\Order::findOrFail($order_id);
            $order->status = $datas['status'];
            $order->save();

            // check if has booked table
            if($order->table_info_id != null) {
                $table_info = Models\TableInfo::findOrFail($order->table_info_id);
                $table_info->status = 1;
                $table_info->user_id = null;
                $table_info->save();
            }

            $result = $order;
            $message = "Successfully Update Order Status";
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

    public function updatePaymentStatus($datas = [], $order_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $order = Models\Order::findOrFail($order_id);
            $order->payment_status = $datas['payment_status'];
            $order->save();

            $result = $order;
            $message = "Successfully Update Order Payment Status";
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
}