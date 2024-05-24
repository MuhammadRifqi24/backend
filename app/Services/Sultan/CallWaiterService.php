<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class CallWaiterService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Call Waiter";
            $status = true;
            switch ($ket) {
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\CallWaiter::with('table_info', 'user', 'cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
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
                    $result = Models\CallWaiter::with('table_info', 'user', 'cafe')->where('user_id', $id)->get();
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
                        $result = Models\CallWaiter::with('table_info', 'user', 'cafe')
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
                    $result = Models\CallWaiter::with('table_info', 'user', 'cafe')->where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\CallWaiter::where('uuid', $id)->first();
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

            $call_waiter = new Models\CallWaiter();
            $call_waiter->user_id = isset($datas['user_id']) ? $datas['user_id'] : null;
            $call_waiter->table_info_id = $datas['table_info_id'];
            $call_waiter->cafe_id = $datas['cafe_id'];
            $call_waiter->note = isset($datas['note']) ? $datas['note'] : null;
            $call_waiter->status = $datas['status'];
            $call_waiter->uuid = $uuid;
            $call_waiter->save();

            $result = $call_waiter;

            $message = "Successfully insert Call Waiter";
            $status = true;

            if ($status === true) DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $code = $e->getCode();
            $message = $e->getMessage();
            $result = [
                'get_file' => $e->getFile(),
                'get_line' => $e->getLine()
            ];
            echo $message;
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'result' => $result
        ];
    }

    public function updateStatus($datas = [], $call_waiter_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $call_waiter = Models\CallWaiter::findOrFail($call_waiter_id);
            $call_waiter->status = $datas['status'];
            $call_waiter->save();

            $result = $call_waiter;
            $message = "Successfully Update Call Waiter";
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
            $call_waiter = Models\CallWaiter::where('uuid', $uuid)->first();
            if ($call_waiter) {
                $call_waiter->delete();
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
}