<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends UuidKeyModel
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_name',
        'display_name',
        'description',
        'birthday',
        'website',
        'is_private',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
