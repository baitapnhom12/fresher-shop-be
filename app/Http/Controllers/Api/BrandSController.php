<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Services\BrandService;

class BrandSController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function list()
    {
        try {
            $brands = $this->brandService->list()->toArray();

            return BrandResource::collection($brands);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }
}
