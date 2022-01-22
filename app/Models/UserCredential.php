<?php

namespace App\Models;

use App\Http\Requests\LoginUserCredentialRequest;
use App\Http\Requests\RegisterUserCredentialRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

class UserCredential extends UuidKeyModel
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
    ];

    protected $hidden = [
        'password_hash',
        'deleted_at',
        'user'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }

    public function userTokens(): HasMany
    {
        return $this->hasMany(UserToken::class, 'user_id');
    }

    public static function verifyCredentialAndIssueToken(LoginUserCredentialRequest $request): ?UserToken
    {
        $storedCredential = UserCredential::where('email', '=', $request->email)->first();

        if ($storedCredential && Hash::check($request->password, $storedCredential->password_hash)) {
            $newToken = new UserToken();
            $newToken->expired_at = now()->addDays(30);
            $storedCredential->userTokens()->save($newToken);

            return $newToken;
        }

        return null;
    }

    public static function hashPassword(RegisterUserCredentialRequest $request): UserCredential
    {
        $hashedUserCredential = new UserCredential();
        $hashedUserCredential->password_hash = Hash::make($request->password);
        $hashedUserCredential->email = $request->email;

        return $hashedUserCredential;
    }
}
