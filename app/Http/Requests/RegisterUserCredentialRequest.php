<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserCredentialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:user_credentials,email',
            'password' => 'required|string|min:8'
        ];
    }
}
