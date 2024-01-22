<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Image;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerService
{
    use ImageTrait;

    protected $bannerModel;

    public function __construct(Banner $bannerModel)
    {
        $this->bannerModel = $bannerModel;
    }

    public function list()
    {
        return $this->bannerModel->with('images:banner_id,path')->latest('id')->get();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $banner = $this->bannerModel->create([
                'name' => $request->name,
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'banners/';
                $fileData = $this->uploads($images, $path);
                $imageData[] = [
                    'path' => $fileData['filePath'],
                    'main' => 0,
                ];
                $imageData[0]['main'] = 1;
                $images = $banner->images()->createMany($imageData);
            }
            DB::commit();

            if ($banner || ($banner && $images)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function edit(string $id)
    {
        return $this->bannerModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $banner = $this->findId($id);
            if (!$banner) {
                throw new \Exception('banner not found', 404);
            }

            $bannerUpdate = $banner->update([
                'name' => $request->name,
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'banners/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => 0,
                    ];
                }
                $imageCreate = $banner->images()->createMany($imageData);
            }

            $imageUpdate = (int) $request->imageUpdate;
            $imageDelete = json_decode($request->imageDelete);
            if ($imageUpdate) {
                Image::where('banner_id', $id)->update(['main' => 0]);
                Image::where('id', $imageUpdate)->update(['main' => 1]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::whereIn('id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            DB::commit();

            if ($bannerUpdate || ($bannerUpdate && $imageCreate)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function findId(string $id)
    {
        return $this->bannerModel->with('images:id,banner_id,path,main')->find($id);
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $banner = $this->findId($id);

            if (!$banner) {
                throw new \Exception('banner not found', 404);
            }

            $imagePathsDelete = $banner->images->pluck('path')->toArray();
            if (!empty($imagePathsDelete)) {
                $this->deleteFile($imagePathsDelete);
            }

            $result = $banner->delete();

            DB::commit();

            if ($result) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }
}
