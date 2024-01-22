<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Image;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandService
{
    use ImageTrait;

    protected $brandModel;

    public function __construct(Brand $brandModel)
    {
        $this->brandModel = $brandModel;
    }

    public function list()
    {
        return $this->brandModel->with('images:brand_id,path')->latest('id')->get();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $brand = $this->brandModel->create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'brands/';
                $fileData = $this->uploads($images, $path);
                $imageData[] = [
                    'path' => $fileData['filePath'],
                    'main' => 0,
                ];
                $imageData[0]['main'] = 1;
                $images = $brand->images()->createMany($imageData);
            }
            DB::commit();

            if ($brand || ($brand && $images)) {
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
        return $this->brandModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $brand = $this->findId($id);
            if (!$brand) {
                throw new \Exception('brand not found', 404);
            }

            $brandUpdate = $brand->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'brands/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => 0,
                    ];
                }
                $imageCreate = $brand->images()->createMany($imageData);
            }

            $imageUpdate = (int) $request->imageUpdate;
            $imageDelete = json_decode($request->imageDelete);
            if ($imageUpdate) {
                Image::where('brand_id', $id)->update(['main' => 0]);
                Image::where('id', $imageUpdate)->update(['main' => 1]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::whereIn('id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            DB::commit();

            if ($brandUpdate || ($brandUpdate && $imageCreate)) {
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
        return $this->brandModel->with('images:id,brand_id,path,main')->find($id);
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $brand = $this->findId($id);

            if (!$brand) {
                throw new \Exception('brand not found', 404);
            }

            $imagePathsDelete = $brand->images->pluck('path')->toArray();
            if (!empty($imagePathsDelete)) {
                $this->deleteFile($imagePathsDelete);
            }

            $result = $brand->delete();

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
