<?php

namespace App\Listeners;

use App\Enums\CouponDefine;
use App\Events\SendCoupon;
use App\Models\Coupon;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendCouponLisrener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendCoupon $event): void
    {
        $subscribers = Subscriber::latest('id')->get();
        $now = Carbon::now()->format('Y/m/d H:i:s');
        $coupons = Coupon::where('expired_at', '>', $now)->latest('id')->get();
        $price = CouponDefine::Price;
        $data = [
            'subscriber' => $subscribers,
            'coupons' => $coupons,
            'price' => $price,
        ];
        foreach ($subscribers as $subscriber) {
            Mail::send('mails.sendcoupon', $data, function ($mesage) use ($subscriber) {
                $mesage->to($subscriber['email']);
                $mesage->subject('Coupon Code');
            });
        }
    }
}
