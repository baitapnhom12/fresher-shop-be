<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discounts = collect($this['discounts'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'discountId' => $item['discount_id'],
                'name' => $item['discount']['name'],
                'percent' => $item['discount']['percent'],
                'active' => $item['discount']['active'],
                'promotionTerm' => $item['promotion_term'],
                'usageCount' => $item['usage_count'],
            ];
        });

        $quantities = collect($this['quantities'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'productId' => $item['product_id'],
                'sizeId' => $item['size_id'],
                'sizeName' => $item['size']['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        });

        $reviews = collect($this['reviews'])->map(fn ($review) => [
            'id' => $review['id'],
            'productId' => $review['product_id'],
            'user' => $review['user'] ? [
                'name' => $review['user']['name'],
                'email' => $review['user']['email'],
                'avatar' => !empty($review['user']['images']) ? collect($review['user']['images'])
                    ->first()['path']
                    : null,
            ] : null,
            'rating' => $review['rating'],
            'comment' => $review['comment'],
            'createdAt' => $review['created_at'],
            'images' => !empty($review['images']) ? $review['images'] : [],
        ]);

        $features = !empty($this['features']) ? collect($this['features'])->map(fn ($item) => [
            'id' => $item['id'],
            'feature' => $item['feature'],
        ]
        ) : [];

        $numberOfReviews = $reviews->unique('user_id')->count();

        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'slug' => $this['slug'],
            'status' => $this['status'],
            'images' => $this['images'],
            'brand' => [
                'id' => $this['brand']['id'],
                'name' => $this['brand']['name'],
                'image' => !empty($this['brand']['images']) ? collect($this['brand']['images'])->first()['path'] ?? null : null,
            ],
            'concentration' => $this['concentration'],
            'categories' => $this['categories'],
            'features' => $features,
            'discounts' => $discounts,
            'quantities' => $quantities,
            'total' => $this['quantity'],
            'quantitySold' => $this['quantitySold'],
            'averageRating' => round($this['averageRating'], 2),
            'reviewsCount' => $this['reviewsCount'],
            'numberOfReviews' => $numberOfReviews,
            'reviews' => $reviews->sortByDesc('createdAt')
                ->toArray(),
            'description' => $this['description'],
            'createdAt' => $this['createdAt'],
            'updatedAt' => $this['updatedAt'],
        ];
    }
}
