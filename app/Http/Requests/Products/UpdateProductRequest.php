<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'categoryIds' => 'nullable|array',
            'categoryIds.*' => 'distinct',
            'brandId' => 'required|integer|exists:brands,id',
            'concentrationId' => 'required|integer|exists:concentrations,id',
            'name' => 'required|string|max:255',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'discountId' => 'nullable',
            'discountId.*' => 'nullable|distinct|integer|exists:discounts,id',
            'promotionTerm' => 'nullable',
            'promotionTerm.*' => 'nullable',
            'usageCount' => 'nullable',
            'usageCount.*' => 'nullable|integer',
            'sizeId' => 'nullable',
            'sizeId.*' => 'nullable|distinct|integer|exists:sizes,id',
            'quantity' => 'nullable',
            'quantity.*' => 'nullable|integer|min:0',
            'price' => 'nullable',
            'price.*' => 'nullable|min:0',
            'description' => 'required|string',
            'imageDelete' => 'nullable',
            'imageUpdate' => 'nullable',
            'sizeDelete' => 'nullable',
            'discountDelete' => 'nullable',
            'status' => 'nullable|integer|min:0|max:1',
            'featureIds' => 'nullable|array',
            'featureIds.*' => 'distinct',
        ];
    }
}
