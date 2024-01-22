<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function uploads($file, $path)
    {
        if ($file) {
            $fileName = time() . $file->getClientOriginalName();
            $src = '';
            if (config('filesystems.default') == 'public') {
                $src = storage_path($path) . '/' . $fileName;
                Storage::put($src, File::get($file));
            }

            if (config('filesystems.default') == 'cloudinary') {
                $src = $path . '/' . $fileName;
                Storage::disk('cloudinary')->put($src, File::get($file));
            }

            $file_name = $file->getClientOriginalName();
            $file_type = $file->getClientOriginalExtension();

            return $file = [
                'fileName' => $file_name,
                'fileType' => $file_type,
                'filePath' => Storage::url($src),
                'fileSize' => $this->fileSize($file),
            ];
        }

        return false;
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }

    public function deleteFile($path)
    {
        array_map(function ($url) {
            $pathInfo = pathinfo(explode('/', $url)[7] . '/' . explode('/', $url)[8]);

            $idFileDelete = $pathInfo['dirname'] . '/' . $pathInfo['filename'];

            if (Storage::disk('cloudinary')->exists($idFileDelete)) {
                return Storage::disk('cloudinary')->delete($idFileDelete);
            }

            return false;
        }, $path);

        return true;
    }
}
