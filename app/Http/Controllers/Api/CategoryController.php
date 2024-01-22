<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateCategotyRequest;
use App\Http\Requests\Categories\EditCategotyRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function findId($id)
    {
        return Category::find($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function listCategories(): JsonResponse
    {
        try {
            $categories = Category::with('children:id,name,parent_id', 'images:id,category_id,path,main,style')->get()->toArray();
            $categories = collect($categories)->map(function ($category) {
                $productsOfCate = Product::whereJsonContains('categories', $category['id'])->count();

                return [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'parent_id' => $category['parent_id'],
                    'slug' => $category['slug'],
                    'images' => $category['images'],
                    'totalProducts' => $productsOfCate,
                    'created_at' => $category['created_at'],
                    'updated_at' => $category['updated_at'],
                    'children' => collect($category['children'])->map(fn ($child) => [
                        'id' => $child['id'],
                        'name' => $child['name'],
                        'grandChildren' => collect($child['children'])->map(fn ($grandChild) => [
                            'id' => $grandChild['id'],
                            'name' => $grandChild['name'],
                        ]),
                    ]),
                ];
            });

            return response()->json($categories);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createCategory(CreateCategotyRequest $request): JsonResponse
    {
        try {
            $parentId = $this->findId($request->parentId);
            if (!$parentId) {
                return response()->json(['message' => 'Not found'], 404);
            }
            $imageJson = json_encode($request->image);
            $result = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parentId,
                'slug' => $request->slug,
                'image' => $imageJson,
            ]);

            return $result ? response()->json(['message' => 'Created successfully'], 201)
                : response()->json(['message' => 'Deleted fail'], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function detailCategory(string $id): JsonResponse
    {
        try {
            $category = Category::with('children:id,name,parent_id', 'images:id,category_id,path,main')->find($id)->toArray();
            if (!$category) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $data = [
                'id' => $category['id'],
                'name' => $category['name'],
                'parent_id' => $category['parent_id'],
                'slug' => $category['slug'],
                'image' => $category['images'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at'],
                'children' => collect($category['children'])->map(fn ($child) => [
                    'id' => $child['id'],
                    'name' => $child['name'],
                    'grandChildren' => collect($child['children'])->map(fn ($grandChild) => [
                        'id' => $grandChild['id'],
                        'name' => $grandChild['name'],
                    ]),
                ]),
            ];

            return response()->json(new CategoryResource($data));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCategory(EditCategotyRequest $request, string $id): JsonResponse
    {
        try {
            $category = $this->findId($id);
            if (!$category) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $result = $category->update([
                'name' => $request->name,
                'parent_id' => $request->parentId,
                'slug' => $request->slug,
                'image' => $request->image,
            ]);

            return $result ? response()->json(['message' => 'Updated successfully'], 200)
                : response()->json(['message' => 'Deleted fail'], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCategory(string $id): JsonResponse
    {
        try {
            $category = $this->findId($id);
            if (!$category) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $result = $category->delete();

            return $result ? response()->json(['message' => 'Deleted successfully'], 200)
                : response()->json(['message' => 'Deleted fail'], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
