<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Post */
class PostResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'user' => $this->author,
            'post' => [
                'id' => $this->id,
                'content' => $this->content,
                'author_id' => $this->author_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'published_at' => $this->published_at,
                'deleted_at' => $this->deleted_at,
            ]
        ];
    }
}
