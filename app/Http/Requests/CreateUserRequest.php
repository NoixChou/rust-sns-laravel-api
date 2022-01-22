<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id_name' => 'required|string|min:3|max:20|unique:users,id_name',
            'display_name' => 'required|string|min:1|max:100',
            'description' => 'present|nullable|string|max:300',
            'birthday' => 'present|nullable|date|before:today',
            'website' => 'present|nullable|url|max:100',
            'is_private' => 'required|boolean'
        ];
    }
}
