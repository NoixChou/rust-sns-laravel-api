<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends UuidKeyModel
{
    use softDeletes;

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
        'birthday',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'is_private' => 'boolean'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id')
            ->where('published_at', '<', now())
            ->orderByDesc('published_at');
    }

    public static function castFromMixed(Mixed $maybeUser): User|null
    {
        if ($maybeUser instanceof User) {
            return $maybeUser;
        }

        return null;
    }
}
