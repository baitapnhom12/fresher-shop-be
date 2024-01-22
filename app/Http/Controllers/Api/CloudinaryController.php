<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cloudinaries\DeleteImagesRequest;
use App\Http\Requests\Cloudinaries\GenerateRequest;
use App\Http\Requests\Cloudinaries\GetImagesFolderRequest;
use App\Http\Requests\Cloudinaries\UploadRequest;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;

class CloudinaryController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
                'url' => [
                    'secure' => true,
                ],
            ],
        ]);
    }

    private function generateFolderPath($tableName)
    {
        $currentDate = now();
        $year = $currentDate->format('Y');
        $month = $currentDate->format('m');
        $day = $currentDate->format('d');

        return "$tableName/$year/$month/$day/";
    }

    /**
     * Upload images to Cloudinary.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadRequest $request)
    {
        try {
            if ($request->hasFile('imagePath')) {
                $images = $request->file('imagePath');
                $folderPath = $this->generateFolderPath($request->tableName);

                $imagePaths = [];
                foreach ($images as $image) {
                    $tempPath = $image->getPathname();

                    $upload = $this->cloudinary->uploadApi()->upload($tempPath, [
                        'folder' => $folderPath,
                    ]);

                    if ($upload) {
                        array_push($imagePaths, $upload['public_id'] . '.' . $upload['format']);
                    }
                }

                return response()->json([
                    'imagePaths' => $imagePaths,
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

    /**
     * Generate images url.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateImageUrls(GenerateRequest $request)
    {
        try {
            $imagePaths = $request->imagePaths;

            if (count($imagePaths)) {
                $cloudinaryImageUrl = env('CLOUDINARY_IMAGE_URL');
                $urls = array_map(function ($path) use ($cloudinaryImageUrl) {
                    return $cloudinaryImageUrl . $path;
                }, $imagePaths);

                return response()->json([
                    'imageUrls' => $urls,
                ], 200);
            }

            return response()->json([
                'error' => 'error',
                'message' => 'generated fail',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get images from folder in Cloudinary.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImagesAssetsFolder(GetImagesFolderRequest $request)
    {
        try {
            $folderName = $request->folderName;
            if ($folderName) {
                $data = $this->cloudinary->adminApi()->assets([
                    'type' => 'upload',
                    'prefix' => $folderName,
                ]);
                $imagePaths = $data['resources'];
                $secureUrls = [];

                foreach ($imagePaths as $image) {
                    $secureUrls[] = ['url' => $image['secure_url']];
                }

                return response()->json($secureUrls, 200);
            }

            return response()->json([
                'error' => 'error',
                'message' => 'getted images fail',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete images from Cloudinary.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAssetsImage(DeleteImagesRequest $request)
    {
        try {
            $input = $request->imagePaths;
            if (count($input)) {
                $input = array_map(function ($path) {
                    $pathInfo = pathinfo($path);

                    return $pathInfo['dirname'] . '/' . $pathInfo['filename'];
                }, $input);

                $result = $this->cloudinary->adminApi()->deleteAssets($input);

                return response()->json($result['deleted'], 200);
            }

            return response()->json([
                'error' => 'error',
                'message' => 'deleted fail',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'validation_error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            if ($request->hasFile('file')) { // Thay đổi 'file' thành tên field trong request của bạn
                $file = $request->file('file');
                $folderPath = $this->generateFolderPath('products');

                $tempPath = $file->getPathname();

                $upload = $this->cloudinary->uploadApi()->upload($tempPath, [
                    'folder' => $folderPath,
                ]);

                if ($upload) {
                    return response()->json(['imagePath' => $upload['public_id'] . '.' . $upload['format']], 200);
                }
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
