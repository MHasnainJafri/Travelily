<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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
            'provider' => 'required|string', // e.g. google.com, facebook.com
            'access_token' => 'required|string', // Firebase ID token
        ];
    }

    public function messages(): array
    {
        return [
            'provider.in' => 'The selected provider is not supported. Allowed: google, facebook, apple.',
        ];
    }
}
