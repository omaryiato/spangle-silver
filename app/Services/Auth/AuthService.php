<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use App\Services\LoggingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function __construct(private AuthRepository $repository, private LoggingService $logging) {}

    public function login(array $data): array
    {
        $user = $this->repository->findByEmail($data['email_address']);
        // Always perform a hash check, including unknown accounts.
        $hash = $user?->password ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        $valid = Hash::isHashed($hash) && Hash::check($data['password'], $hash);
        if (! $valid || ! $user || (int) $user->status !== 1) {
            throw new HttpException(401, 'Invalid credentials.');
        }

        return DB::transaction(function () use ($user): array {
            $user = $this->repository->lockUser($user);
            if ((int) $user->status !== 1) {
                throw new HttpException(401);
            }
            $this->repository->revokeAll($user);
            $token = $this->repository->issue($user);
            DB::afterCommit(function () use ($user): void {
                $this->logging->record('Login', (int) $user->id, 'SUCCESS');
            });

            return ['user' => $user, 'token' => $token->plainTextToken, 'expires_at' => $token->accessToken->expires_at];
        });
    }

    public function signup(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            $user = $this->repository->create([
                'full_name' => $data['full_name'], 'user_name' => $data['user_name'],
                'email_address' => $data['email_address'], 'phone_number' => $data['phone_number'] ?? null,
                'password' => Hash::make($data['password']), 'status' => 1, 'user_type' => 0,
            ]);
            $token = $this->repository->issue($user);
            DB::afterCommit(function () use ($user): void {
                $this->logging->record('Signup', (int) $user->id, 'SUCCESS');
            });

            return ['user' => $user, 'token' => $token->plainTextToken, 'expires_at' => $token->accessToken->expires_at];
        });
    }

    public function logout(User $user): void
    {
        $this->repository->revokeCurrent($user);
        $this->logging->record('Logout', (int) $user->id, 'SUCCESS');
    }

    /** Rotate an unexpired bearer token; expired tokens require a new login. */
    public function refresh(User $user, int $tokenId): array
    {
        return DB::transaction(function () use ($user, $tokenId): array {
            $user = $this->repository->lockUser($user);
            if ((int) $user->status !== 1 || $user->deleted_at !== null || ! $this->repository->tokenExists($user, $tokenId)) {
                throw new HttpException(401);
            }
            $this->repository->revokeAll($user);
            $next = $this->repository->issue($user);
            DB::afterCommit(function () use ($user): void {
                $this->logging->record('RefreshToken', (int) $user->id, 'SUCCESS');
            });

            return ['user' => $user, 'token' => $next->plainTextToken, 'expires_at' => $next->accessToken->expires_at];
        });
    }
}
