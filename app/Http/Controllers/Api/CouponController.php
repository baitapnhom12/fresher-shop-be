<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Coupon\CouponSkuResources;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class CouponController extends Controller
{
    public function show(string $sku)
    {
        try {
            $now = Carbon::now()->format('Y/m/d H:i:s');
            $coupon = Coupon::where('sku', $sku)->where('expired_at', '>', $now)->first();

            return Response::json(new CouponSkuResources($coupon));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
