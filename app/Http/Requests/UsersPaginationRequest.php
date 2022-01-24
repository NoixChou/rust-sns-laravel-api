<?php

namespace App\Http\Requests;

class UsersPaginationRequest extends QueryParameterRequest
{
    public function rules(): array
    {
        return [
            'latest_post_id' => 'nullable|uuid|exists:posts,id',
            'oldest_post_id' => 'nullable|uuid|exists:posts,id',
        ];
    }
}
