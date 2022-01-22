<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserToken extends UuidKeyModel
{
    public $incrementing = false;
    protected $primaryKey = 'token';
    protected $keyType = 'string';

    protected $fillable = [];

    protected $hidden = [
        'token',
        'user_id',
        'expired_at',
        'deleted_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function userCredential(): BelongsTo
    {
        return $this->belongsTo(UserCredential::class, 'user_id');
    }

    public static function fetchByTokenString(string $tokenString): ?UserToken
    {
        return UserToken
            ::where('token', '=', $tokenString)
            ->where('deleted_at', '=', null)
            ->where('expired_at', '>', now())
            ->first();
    }
}
