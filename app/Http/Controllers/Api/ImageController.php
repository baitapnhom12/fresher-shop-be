<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cloudinaries\UploadRequest;
use App\Models\Image;
use App\Traits\ImageTrait;

class ImageController extends Controller
{
    use ImageTrait;

    public function upload(UploadRequest $request)
    {
        try {
            $path = storage_path($request->tableName);
            !is_dir($path) &&
                mkdir($path, 0777, true);
            if ($request->hasFile('imagePath')) {
                $image = $request->file('imagePath');
                $fileData = $this->uploads($image, $path);

                if ($request->user_id) {
                    Image::create([
                        'user_id' => $request->user_id,
                        'path' => $fileData['filePath'],
                        'main' => $request->main === 'true' ? 1 : 0,
                    ]);
                }

                if ($request->product_id) {
                    Image::create([
                        'product_id' => $request->product_id,
                        'path' => $fileData['filePath'],
                        'main' => $request->main === 'true' ? 1 : 0,
                    ]);
                }

                if ($request->category_id) {
                    Image::create([
                        'category_id' => $request->category_id,
                        'path' => $fileData['filePath'],
                        'main' => $request->main === 'true' ? 1 : 0,
                    ]);
                }

                if ($request->brand_id) {
                    Image::create([
                        'brand_id' => $request->brand_id,
                        'path' => $fileData['filePath'],
                        'main' => $request->main === 'true' ? 1 : 0,
                    ]);
                }

                if ($request->banner_id) {
                    Image::create([
                        'banner_id' => $request->banner_id,
                        'path' => $fileData['filePath'],
                        'main' => $request->main === 'true' ? 1 : 0,
                    ]);
                }

                return response()->json([
                    'imagePaths' => $fileData['filePath'],
                ], 200);
            }

            return response()->json([
                'error' => 'error',
                'message' => 'Uploaded fail',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
