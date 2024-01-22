<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategotyRequest extends FormRequest
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
            'name' => 'required|string|max:50|unique:categories,name',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'parentId' => 'nullable|integer|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.unique' => 'The name has already been taken.',
            'name.max' => 'Name is over max characters',
            'parentId.exists' => 'Not an existing ID',
            'images.image' => 'Images must be image',
            'images.mimes' => 'Images must be jpeg,png,jpg,gif,webp',
            'images.max' => 'only upload a maximum of 5 images at a time ',
        ];
    }
}
