<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
