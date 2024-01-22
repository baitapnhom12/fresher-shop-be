<?php

namespace App\Http\Requests\coupon;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|max:20|unique:coupons,sku',
            'usage_count' => 'required|max:11|regex:/^[0-9]+$/',
            'discount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'type' => 'required',
            'expired_at' => 'required',
        ];
    }
}
