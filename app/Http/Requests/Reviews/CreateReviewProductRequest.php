<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateReviewProductRequest extends FormRequest
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
            'productId' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*.' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
