<?php

namespace App\Services;

use App\Models\FavoriteProduct;

class FavoriteProductService
{
    private $model;

    public function __construct(FavoriteProduct $model)
    {
        $this->model = $model;
    }

    public function listFavoriteProduct()
    {
        try {
            $favoriteProducts = $this->model::with(
                'product:id,name,description,slug',
                'product.quantities:id,product_id,size_id,quantity,price',
                'product.quantities.size:id,name',
                'product.images:id,product_id,path,main'
            )->where('user_id', auth()->user()->id)->get();

            $favoriteProducts = collect($favoriteProducts)->map(function ($product) {
                return [
                    'id' => $product['product']['id'],
                    'name' => $product['product']['name'],
                    'slug' => $product['product']['slug'],
                    'images' => collect($product['product']['images'])->map(fn ($image) => [
                        'id' => $image['id'],
                        'path' => $image['path'],
                        'main' => $image['main'] ? true : false,
                    ]),
                    'quantities' => collect($product['product']['quantities'])->map(fn ($item) => [
                        'id' => $item['id'],
                        'productId' => $item['product_id'],
                        'sizeId' => $item['size_id'],
                        'sizeName' => $item['size']['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]),
                    'description' => $product['product']['description'],
                ];
            });

            return response()->json($favoriteProducts);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addOrRemoveFavoriteProduct($request)
    {
        try {
            $product = FavoriteProduct::where(['user_id' => auth()->user()->id, 'product_id' => $request->productId])->first();
            if ($product) {
                $product->delete();
            } else {
                FavoriteProduct::create(['user_id' => auth()->user()->id, 'product_id' => $request->productId]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
