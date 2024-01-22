<?php

namespace App\Http\Requests\Cloudinaries;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadRequest extends FormRequest
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
            'imagePath' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'user_id' => 'nullable|integer|exists:users,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'banner_id' => 'nullable|integer|exists:banners,id',
            'tableName' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
