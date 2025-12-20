<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $auth) {}

    public function register(RegisterRequest $request)
    {
        return $this->auth->register($request);
    }

    public function sendOtp(Request $request): array|JsonResponse
    {
        return $this->auth->sendOtp($request->email);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        return $this->auth->verifyOtp($request);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        return $this->auth->updateProfile($request);
    }
}
