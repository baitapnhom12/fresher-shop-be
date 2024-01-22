<?php

namespace App\Http\Resources\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponSkuResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'discount' => $this->discount,
            'type' => $this->type,
        ];
    }
}
