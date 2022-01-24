<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends UuidKeyModel
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'content',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
