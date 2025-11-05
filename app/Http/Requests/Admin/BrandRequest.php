<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exception\HttpResponseException;
use App\Models\Brand;

class BrandRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'url' => 'required|regex:/^[\pL\s\-]+$/u',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'url.required' => 'The url field is required.',

        ];
    }

    protected function prepareForValidation()
    {
        if ($this->route('brand')) {
            $this->merge([
                'id' => $this->route('brand'),
            ]);
        }
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier si l'URL de la marque existe déjà
            $brandQuery = Brand::where('url', $this->input('url'));

            // Si on est en update, exclure l'ID courant
            if ($this->filled('id')) {
                $brandQuery->where('id', '!=', $this->input('id'));
            }

            if ($brandQuery->count() > 0) {
                $validator->errors()->add('url', 'Brand already exists!');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)  // Envoie les erreurs à la session
                ->withInput()             // Garde les anciennes valeurs
        );
    }
}
