<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\DiscountPaginationResource;
use App\Services\DiscountService;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function list()
    {
        try {
            return response()->json(new DiscountPaginationResource($this->discountService->paginate()));
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
