<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;

class FileUploadService
{
    public static function uploadProduct($file, $name, $update = null)
    {
        $result = [];
        try {
            if ($update != null) {
                $cekimage = public_path('images/product/' . $update);
                if (file_exists($cekimage)) unlink($cekimage);
                $cekthumbnail = public_path('images/product/thumbnail/' . $update);
                if (file_exists($cekthumbnail)) unlink($cekthumbnail);
            }
            $image = $file->move(public_path() . '/images/product/temp/', $name);
            //resize image aspect ratio
            $original = Image::make($image);
            if ($original->width() > 800) {
                $original->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $ori = $original->save(public_path('images/product/' . $name));

            $thumbnail = Image::make($image);
            if ($thumbnail->width() > 250) {
                $thumbnail->resize(250, 250, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $thumb = $thumbnail->save(public_path('images/product/thumbnail/' . $name));

            if ($ori && $thumb) {
                if (file_exists($image)) unlink($image);
            }

            $status = true;
        } catch (\Exception $e) {
            $status = false;
            $result = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
        }

        return [
            'status' => $status,
            'result' => $result
        ];
    }
}
