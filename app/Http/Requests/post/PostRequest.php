<?php

namespace App\Http\Requests\post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|max:255|unique:posts,title',
            'content' => 'required',
            'active' => 'required',
            'popular' => 'required',
            'articles' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
