<?php

namespace App\Services;

use App\Enums\ImageDefine;
use App\Enums\PaginationDefine;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Traits\ImageTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    use ImageTrait;

    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category;
    }

    public function listCategoryies()
    {
        return $this->categoryModel->with('images:id,category_id,path,main', 'parent:id,name')->orderByDesc('created_at')->get();
    }

    public function listCategoryiesPagiate()
    {
        return $this->categoryModel->with('images:id,category_id,path,main', 'parent:id,name')->select('id', 'name', 'parent_id', 'slug')->orderByDesc('created_at')->paginate(PaginationDefine::PaginateDefault);
    }

    public function storeCategory($request)
    {
        try {
            DB::beginTransaction();
            if ($request->parentId) {
                $parentId = $this->findId($request->parentId);
                !$parentId &&
                    throw new \Exception('Category not found', 404);
            }

            $category = $this->categoryModel->create([
                'name' => $request->name,
                'parent_id' => $request->parentId,
                'slug' => Str::slug($request->name),
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'categories/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $imageData[0]['main'] = ImageDefine::ImageMain;
                $images = $category->images()->createMany($imageData);
            }
            DB::commit();

            if ($category || ($category && $images)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function findId($id)
    {
        return $this->categoryModel->with('images:id,category_id,path,main,style')->find($id);
    }

    public function updateCategory($request, $id)
    {
        try {
            DB::beginTransaction();
            $category = $this->findId($id);
            if (!$category) {
                throw new \Exception('Category not found', 404);
            }

            $categoryUpdate = $category->update([
                'name' => $request->name,
                'parent_id' => $request->parentId,
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'categories/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $imageCreate = $category->images()->createMany($imageData);
            }

            $imageUpdate = (int) $request->imageUpdate;
            $imageDelete = json_decode($request->imageDelete);
            $imageUpdateStyle = json_decode($request->imageUpdateStyle);
            if (!empty($imageUpdateStyle)) {
                foreach ($imageUpdateStyle as $item) {
                    Image::where([
                        'id' => $item[0],
                        'category_id' => $id,
                    ])->update(['style' => $item[1]]);
                }
            }

            if ($imageUpdate) {
                Image::where('category_id', $id)->update(['main' => ImageDefine::ImageNotMain]);
                Image::where('id', $imageUpdate)->update(['main' => ImageDefine::ImageMain]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::where('category_id', $id)->whereIn('id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            DB::commit();

            if ($categoryUpdate || ($categoryUpdate && $imageCreate)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function deleteCategory($id)
    {
        try {
            DB::beginTransaction();
            $category = $this->findId($id);

            if (!$category) {
                throw new \Exception('Category not found', 404);
            }

            $products = Product::whereJsonContains('categories', (int) $id)->get(['id', 'categories']);
            foreach ($products as $product) {
                $categories = collect(json_decode($product->categories))->filter(function ($category) use ($id) {
                    return $category !== (int) $id;
                })->values()->toArray();

                $product->categories = empty($categories) ? null : $categories;
                $product->save();
            }

            $imagePathsDelete = $category->images->pluck('path')->toArray();
            if (!empty($imagePathsDelete)) {
                $this->deleteFile($imagePathsDelete);
            }

            $category->images()->delete();
            $result = $category->delete();

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

    public function detail($id): JsonResponse
    {
        $result = $this->findId($id);
        try {
            if (!$result) {
                return response()->json(['message' => 'not found'], 404);
            }
            $result = $result->toArray();

            $data = [
                'id' => $result['id'],
                'name' => $result['name'],
                'parentId' => $result['parent_id'],
                'slug' => $result['slug'],
            ];

            return response()->json(new CategoryResource($data));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
?>

