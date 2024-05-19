<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TableInfoService
{
    public function getDataByID($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Table Info";
            $status = true;
            switch ($ket) {
                case 'user_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['user_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\TableInfo::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
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
                case 'cafe_id':
                    $cafeManagement = Models\CafeManagement::select('id', 'user_id', 'cafe_id')->where(['cafe_id' => $id, 'status' => true])->first();
                    if ($cafeManagement) {
                        $message .= ' by CafeId';
                        $result = Models\TableInfo::with('cafe')->where('cafe_id', $cafeManagement->cafe_id)->get();
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
                    $result = Models\TableInfo::with('cafe')->where('uuid', $id)->first();
                    if(!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                case 'status':
                    $result = Models\TableInfo::where(['uuid' => $id, 'status' => true])->first();
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $status = false;
                    }
                    break;
                default:
                    $result = Models\TableInfo::where('uuid', $id)->first();
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

    private function getLastMaxNumber($cafe_id) {
        $result = Models\TableInfo::select('number')->where('cafe_id', $cafe_id)->latest()->get();
        if(count($result) == 0) {
            $max = null;
        } else {
            $max = $result[0]->number;

            foreach ($result as $key => $value) {
                if($value->number > $max) {
                    $max = $value->number;
                }
            }
        }

        return $max;
    }

    public function insertData($datas = [])
    {
        $status = false;
        $code = 200;
        $result = null;

        DB::beginTransaction();
        try {
            $max_number = $this->getLastMaxNumber($datas['cafe_id']);
            if($max_number == null) {
                $max_number = 1;
            } else {
                $max_number++;
            }

            $data = [];
            
            $total = ($datas['total']-1) + $max_number;
            for ($i = $max_number; $i <= $total; $i++) {
                $uuid = Str::uuid()->getHex()->toString();
                $data[] = [
                    'cafe_id' => $datas['cafe_id'],
                    'name' => 'Meja',
                    'number' => $i,
                    'status' => true,
                    'uuid' => $uuid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $table_info = Models\TableInfo::insert($data);

            $result = $table_info;
            $message = "Successfully insert Table Info";
            $status = true;
            $code = 201;

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

    public function updateData($datas = [], $table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            $table_info->user_id = $datas['user_id'] == null ? null : $datas['user_id'];
            $table_info->name = $datas['name'] == null ? $table_info->name : $datas['name'];
            $table_info->status = $datas['status'] == null ? $table_info->status : $datas['status'];
            $table_info->save();

            $result = $table_info;
            $message = "Successfully Update Table Info";
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
            $table_info = Models\TableInfo::where('uuid', $uuid)->first();
            
            if ($table_info) {
                $last_number = $this->getLastMaxNumber($table_info->cafe_id);
                if($last_number == $table_info->number) {
                    $table_info->delete();

                    $status = true;
                    $result = true;
                    $message = 'Successfully delete Table Info';
                } else {
                    $code = 404;
                    $message = 'Data tidak boleh dihapus';
                }
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

    public function bookTable($datas = [], $table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            if ($table_info->status == 1) {
                $table_info->user_id = $datas['user_id'] == null ? null : $datas['user_id'];
                $table_info->status = 0;
                $table_info->save();
    
                $result = $table_info;
                $message = "Successfully Booking Table";
                $status = true;
            } else {
                $result = $table_info;
                $message = "Failed to Book a Table, Already Booked";
                $status = false;
                $code = 409;
            }
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

    public function finishTable($table_info_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $table_info = Models\TableInfo::findOrFail($table_info_id);
            $table_info->status = 1;
            $table_info->user_id = null;
            $table_info->save();

            $result = $table_info;
            $message = "Successfully Made the Table Available";
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