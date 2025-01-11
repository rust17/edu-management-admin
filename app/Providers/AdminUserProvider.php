<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class AdminUserProvider implements UserProvider
{
    /**
     * @inheritdoc
     */
    public function retrieveById($identifier)
    {
        return User::where('id', $identifier)
            ->whereIn('role', ['admin', 'teacher'])
            ->first();
    }

    /**
     * @inheritdoc
     */
    public function retrieveByToken($identifier, $token)
    {
        if (!$user = User::where('id', $identifier)
            ->whereIn('role', ['admin', 'teacher'])
            ->first()
        ) {
            return null;
        }

        $rememberToken = $user->remember_token;

        return $rememberToken && hash_equals($rememberToken, $token) ? $user : null;
    }

    /**
     * @inheritdoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        /** @var User $user */
        $user->remember_token = $token;
        $user->save();
    }

    /**
     * @inheritdoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['username']) || !isset($credentials['password'])) {
            return null;
        }

        return User::where('email', $credentials['username'])
            ->whereIn('role', ['admin', 'teacher'])
            ->first();
    }

    /**
     * @inheritdoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
