<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; // ✅ bon namespace
use Illuminate\Http\Exceptions\HttpResponseException; // ✅ attention au "Exceptions" (pas "Exception")
use App\Models\Category;

class CategoryRequest extends FormRequest
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
            'category_name' => 'required',
            'url' => 'required | regex:/^[\pL\s\-]+$/u'
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'Category name is required',
            'url.required' => 'URL is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }

    protected function prepareForValidation()
    {
        if ($this->route('category')) {
            $this->merge([
                'id' => $this->route('category'),
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoryCount = Category::where('url', $this->input('url'));
            if ($this->filled('id')) {
                $categoryCount->where('id', ' != ', $this->input('id'));
            }
            if ($categoryCount->count() > 0) {
                $validator->errors()->add('url', 'Category already exists!');
            }
        });
    }
}
