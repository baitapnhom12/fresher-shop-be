<?php

namespace App\Services;

use App\Enums\ImageDefine;
use App\Models\Product;
use App\Models\Review;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    use ImageTrait;

    private $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function storeProductReview($request)
    {
        try {
            $product = Product::find($request->productId, ['id']);
            if (!$product) {
                return response()->json('Product not found', 400);
            }

            DB::beginTransaction();
            $review = $this->model->create([
                'product_id' => $request->productId,
                'user_id' => auth()->user()->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $images = $request->file('images');
            if (!empty($images)) {
                $path = 'reviews/';

                foreach ($images as $img) {
                    $fileData = $this->uploads($img, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $review->images()->createMany($imageData);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
