<?php

namespace App\Services\Sultan;

use App\Models;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StanService
{
    public function getStan($id, $ket = "")
    {
        $status = false;
        $code = 200;
        $result = null;
        try {
            $message = "Get data Stan";
            $status = true;
            $cacheStanName = "stan_{$ket}_{$id}";
            switch ($ket) {
                case 'user_id':
                    $result = Cache::tags(['get_list_stan'])->remember($cacheStanName, now()->addMinute(150), function () use($id){
                        return Models\Stan::where('user_id', $id)->first();
                    });
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'uuid':
                    $result = Cache::tags(['get_list_stan'])->remember($cacheStanName, now()->addMinute(150), function () use($id){
                        return Models\Stan::where('uuid', $id)->first();
                    });
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'stan_id':
                    $result = Cache::tags(['get_list_stan'])->remember($cacheStanName, now()->addMinute(150), function () use($id){
                        return Models\Stan::where('id', $id)->first();
                    });
                    if (!$result) {
                        $code = 404;
                        $message = 'Data Not Found';
                        $result = null;
                    }
                    break;
                case 'get_info':
                    $cafe_management = Cache::tags(['get_list_stan'])->remember("cafe_management_{$id}", now()->addMinute(150), function () use($id){
                        return  Models\CafeManagement::where('user_id', $id)->first();
                    });
                    if ($cafe_management) {
                        $cafe_id = $cafe_management->cafe_id;
                        $stan_id = $cafe_management->stan_id;
                    } else {
                        $status = false;
                        $cafe_id = null;
                        $stan_id = null;
                    }
                    $result = [
                        'cafe_id' => $cafe_id,
                        'stan_id' => $stan_id
                    ];
                    break;
                default:
                    $result = Cache::tags(['get_list_stan'])->remember("cafe_{$id}", now()->addMinute(150), function () use($id){
                        return  Models\Cafe::findOrFail($id);
                    });
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

    public function updateData($datas = [], $stan_id)
    {
        $status = false;
        $code = 200;
        $result = null;
        DB::beginTransaction();
        try {
            $stan = Models\Stan::findOrFail($stan_id);
            $stan->name  = $datas['name'];
            $stan->description = $datas['description'];
            $stan->logo = $datas['logo'];
            $stan->status = $datas['status'];
            $stan->save();

            $result = $stan;
            $message = "Successfully Update Stan";
            $status = true;
            Cache::tags('get_list_stan')->flush();
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
