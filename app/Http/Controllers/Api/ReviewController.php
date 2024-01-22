<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\CreateReviewProductRequest;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    private $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function storeProductReview(CreateReviewProductRequest $request)
    {
        return $this->reviewService->storeProductReview($request);
    }
}
