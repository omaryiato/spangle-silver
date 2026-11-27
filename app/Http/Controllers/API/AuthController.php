<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Login\Login;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function login(Login $request): JsonResponse
    {
        return ResponseHelper::success(AuthResource::make($this->service->login($request->validated())), ['ar' => 'تم تسجيل الدخول', 'en' => 'Logged in successfully']);
    }

    public function signup(SignupRequest $request): JsonResponse
    {
        return ResponseHelper::success(AuthResource::make($this->service->signup($request->validated())), ['ar' => 'تم إنشاء الحساب', 'en' => 'Account created'], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request->user());

        return ResponseHelper::success(null, ['ar' => 'تم تسجيل الخروج', 'en' => 'Logged out successfully']);
    }

    public function refresh(Request $request): JsonResponse
    {
        return ResponseHelper::success(AuthResource::make($this->service->refresh($request->user(), (int) $request->user()->currentAccessToken()->getKey())), ['ar' => 'تم تجديد الرمز', 'en' => 'Token rotated']);
    }

    public function me(Request $request): JsonResponse
    {
        return ResponseHelper::success(UserResource::make($request->user()));
    }
}
