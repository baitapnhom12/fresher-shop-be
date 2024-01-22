<?php

namespace App\Http\Controllers;

use App\Enums\CouponDefine;
use App\Events\SendCoupon;
use App\Http\Requests\coupon\CouponRequest;
use App\Http\Resources\Coupon\CouponResources;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Coupon;
use App\Services\CouponService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;

class CouponController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return CouponResources::collection($this->couponService->list());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        try {
            $this->couponService->store($request);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $result = $this->couponService->show($request->id);
        if (!$result) {
            return Response::json([
                'message' => 'Discounts not found',
            ]);
        }

        return new CouponResources($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'sku' => 'required|max:20|unique:coupons,sku,' . $id,
            'usage_count' => 'required|max:11|regex:/^[0-9]+$/',
            'discount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'type' => 'required',
            'expired_at' => 'required',
        ]);
        try {
            $this->couponService->update($request, $id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $this->couponService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sendCoupon()
    {
        try {
            Event::dispatch(new SendCoupon(1));

            return new SuccessResource;
        } catch (\Throwable $th) {
            return new ErrorResource;
        }
    }

    public function mailCoupon()
    {
        $now = Carbon::now()->format('Y/m/d H:i:s');
        $coupons = Coupon::where('expired_at', '>', $now)->latest('id')->get();
        $price = CouponDefine::Price;

        return view('mails.sendcoupon', compact('coupons', 'price'));
    }
}
