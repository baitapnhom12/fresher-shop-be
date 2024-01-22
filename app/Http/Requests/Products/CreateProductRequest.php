<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'status' => 'nullable|integer|min:0|max:1',
            'featureIds' => 'nullable|array',
            'featureIds.*' => 'distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'categoryIds' => [
                'array' => 'The category IDs field must be an array.',
                'distinct' => 'Duplicate category IDs are not allowed.',
            ],
            'brandId' => [
                'required' => 'The brand field is required.',
                'integer' => 'The brand ID must be a valid integer.',
                'exists' => 'The selected brand does not exist.',
            ],
            'concentrationId' => [
                'required' => 'The concentration field is required.',
                'integer' => 'The concentration ID must be a valid integer.',
                'exists' => 'The selected concentration does not exist.',
            ],
            'name' => [
                'required' => 'The name field is required.',
                'string' => 'The name must be a string.',
                'max' => 'The name must not be longer than 255 characters.',
            ],
            'images' => [
                'array' => 'The images field must be an array.',
                'image' => 'Each image must be a valid image file.',
                'mimes' => 'Supported image formats are JPEG, PNG, JPG, GIF, and WEBP.',
                'max' => 'Each image must not exceed 3072 KB in size.',
            ],
            'discountId' => [
                'nullable' => 'The discount ID field is optional.',
                'integer' => 'The discount ID must be a valid integer.',
                'exists' => 'The selected discount does not exist.',
            ],
            'promotionTerm' => [
                'nullable' => 'The promotion term field is optional.',
                'string' => 'The promotion term must be a string.',
            ],
            'usageCount' => [
                'nullable' => 'The usage count field is optional.',
                'integer' => 'The usage count must be an integer.',
                'min' => 'The usage count must be non-negative.',
            ],
            'sizeId' => [
                'nullable' => 'The size ID field is optional.',
                'integer' => 'The size ID must be a valid integer.',
                'exists' => 'The selected size does not exist.',
            ],
            'quantity' => [
                'required' => 'The quantity field is required.',
                'integer' => 'The quantity must be an integer.',
                'min' => 'The quantity must be non-negative.',
            ],
            'price' => [
                'required' => 'The price field is required.',
                'min' => 'The price must be non-negative.',
            ],
            'description' => [
                'required' => 'The description field is required.',
                'string' => 'The description must be a string.',
            ],
            'featureIds' => [
                'array' => 'The category IDs field must be an array.',
                'distinct' => 'Duplicate category IDs are not allowed.',
            ],
        ];
    }
}
