<?php

declare(strict_types=1);

namespace App\Repositories\Auth;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class AuthRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email_address', $email)->whereNull('deleted_at')->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function revokeAll(User $user): void
    {
        $user->tokens()->delete();
    }

    public function issue(User $user): NewAccessToken
    {
        return $user->createToken('auth_token', ['*'], now()->addHours(10));
    }

    public function revokeCurrent(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function lockUser(User $user): User
    {
        return User::whereKey($user->getKey())->lockForUpdate()->firstOrFail();
    }

    public function tokenExists(User $user, int $tokenId): bool
    {
        return $user->tokens()->whereKey($tokenId)->exists();
    }
}
