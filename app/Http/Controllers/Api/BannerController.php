<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerResources;
use App\Services\BannerService;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function list()
    {
        try {
            $banners = $this->bannerService->list()->toArray();

            return BannerResources::collection($banners);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
