<?php

namespace App\Http\Requests\Orders;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckoutRequest extends FormRequest
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
            'receiver' => 'required|string',
            'phone' => 'required|string',
            'shippingAddress' => 'required|string',
            'shippingFee' => 'required|numeric',
            'paymentMethod' => 'required|numeric',
            'provider' => 'nullable|string|max:100',
            'accountNumber' => 'nullable|regex:/^\d{1,20}$/',
            'products' => 'required|array',
            'products.*.productId' => 'required|integer|exists:quantities,product_id',
            'products.*.quantity' => 'required|numeric',
            'products.*.price' => 'required|numeric',
            'products.*.sizeId' => 'required|integer|exists:quantities,size_id',
            'products.*.sizeName' => 'required|string',
            'products.*.discountId' => 'nullable|integer|exists:discount_products,discount_id',
            'discount' => 'required|numeric',
            'couponCode' => 'nullable|string|max:20|exists:coupons,sku',
            'total' => 'required|numeric',
            'subTotal' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
