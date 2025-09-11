<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * @param array $data
     * @return String
     * @throws AuthenticationException
     */
    public function login(array $data): string
    {
        $user = User::query()->where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        return $user->createToken('auth-token')->plainTextToken;
    }

    /**
     * @param User $user
     * @return void
     *
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
