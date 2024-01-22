<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponService
{
    protected $couponModel;

    public function __construct(Coupon $couponModel)
    {
        $this->couponModel = $couponModel;
    }

    public function list()
    {
        return $this->couponModel->latest('id')->get();
    }

    public function paginate()
    {
        return $this->couponModel->latest('id')->paginate(10)->toArray();
    }

    public function store(Request $request)
    {
        $coupon = $this->couponModel->create([
            'sku' => Str::sku($request->sku),
            'usage_count' => $request->usage_count,
            'discount' => $request->discount,
            'type' => $request->type,
            'expired_at' => $request->expired_at,
        ]);

        $users = Subscriber::all('email')->pluck('email');
    }

    public function show(string $id)
    {
        return $this->couponModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $discount = $this->couponModel->find($id);
        $discount->update([
            'usage_count' => $request->usage_count,
            'discount' => $request->discount,
            'expired_at' => $request->expired_at,
        ]);
    }

    public function destroy(string $id)
    {
        $discount = $this->couponModel->find($id);
        $discount->delete();
    }
}
