<?php

namespace App\Services\Sultan;

use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadService
{
    public static function uploadProduct($file, $name, $update = null)
    {
        $result = [];
        try {
            $manager = new ImageManager(new Driver());
            if ($update != null) {
                $cekimage = public_path('images/product/' . $update);
                if (file_exists($cekimage)) unlink($cekimage);
                $cekthumbnail = public_path('images/product/thumbnail/' . $update);
                if (file_exists($cekthumbnail)) unlink($cekthumbnail);
            }
            $image = $file->move(public_path() . '/images/product/temp/', $name);
            //resize image aspect ratio
            $original = $manager->read($image);
            if ($original->width() > 800) {
                $original->scale(800, 800);
            }
            $ori = $original->save(public_path('images/product/' . $name));

            $thumbnail = $manager->read($image);
            if ($thumbnail->width() > 250) {
                $thumbnail->scale(250, 250);
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

    public static function uploadCafe($file, $name, $update = null)
    {
        $result = [];
        try {
            // cek jika folder ada
            $targetDirectory = public_path('images/cafe/');
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }
            
            $thumbnailDirectory = public_path('images/cafe/thumbnail/');
            if (!is_dir($thumbnailDirectory)) {
                mkdir($thumbnailDirectory, 0777, true);
            }

            $tempDirectory = public_path('images/cafe/temp/');
            if (!is_dir($tempDirectory)) {
                mkdir($tempDirectory, 0777, true);
            }

            $manager = new ImageManager(new Driver());
            if ($update != null) {
                $cekimage = public_path('images/cafe/' . $update);
                if (file_exists($cekimage)) unlink($cekimage);
                $cekthumbnail = public_path('images/cafe/thumbnail/' . $update);
                if (file_exists($cekthumbnail)) unlink($cekthumbnail);
            }
            $image = $file->move(public_path() . '/images/cafe/temp/', $name);
            //resize image aspect ratio
            $original = $manager->read($image);
            if ($original->width() > 800) {
                $original->scale(800, 800);
            }
            $ori = $original->save(public_path('images/cafe/' . $name));

            $thumbnail = $manager->read($image);
            if ($thumbnail->width() > 250) {
                $thumbnail->scale(250, 250);
            }
            $thumb = $thumbnail->save(public_path('images/cafe/thumbnail/' . $name));

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
