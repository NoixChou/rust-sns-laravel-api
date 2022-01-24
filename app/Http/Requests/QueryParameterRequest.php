<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryParameterRequest extends FormRequest
{
    public function validationData(): array
    {
        return array_merge($this->all(), $this->query->all());
    }
}
