<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'mobile' => 'required|numeric|digits:10',
            'image' => 'image',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.regex' => 'Name must contain only letters, spaces, and hyphens',
            'name.max' => 'Name must not exceed 255 characters',
            'mobile.required' => 'Mobile number is required',
            'mobile.numeric' => 'Mobile number must be numeric',
            'mobile.digits' => 'Mobile number must be 10 digits',
            'image.image' => 'Image must be an image',
        ];
    }
}
