<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'current_pwd' => ['required', 'string', 'min:6', 'max:12'],
            'new_pwd' => ['required', 'string', 'min:6', ' max:12', 'different:current_pwd'],
            'confirm_pwd' => ['required', 'string', 'min:6', 'max:12', 'same:new_pwd'],
        ];
    }

    public function messages()
    {
        return [
            'current_pwd.required' => 'Current password is required',
            'current_pwd.min' => 'Current password must be at least 6 characters',
            'current_pwd.max' => 'Current password must be at most 12 characters',
            'new_pwd.required' => 'New password is required',
            'new_pwd.min' => 'New password must be at least 6 characters',
            'new_pwd.max' => 'New password must be at most 12 characters',
            'confirm_pwd.required' => 'Confirm password is required',
            'confirm_pwd.min' => 'Confirm password must be at least 6 characters',
            'confirm_pwd.max' => 'Confirm password must be at most 12 characters',
            'new_pwd.different' => 'New password and current password must be different',
            'confirm_pwd.same' => 'Confirm password must match new password',
        ];
    }
}
