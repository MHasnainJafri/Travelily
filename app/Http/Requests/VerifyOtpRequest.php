<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email',
            'otp' => 'required|digits:4',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
