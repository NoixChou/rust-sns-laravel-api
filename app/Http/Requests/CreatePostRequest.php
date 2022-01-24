<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'required|string|min:1|max:1000',
            'is_publish' => 'required|boolean'
        ];
    }
}
