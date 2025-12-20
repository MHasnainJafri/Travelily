<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use App\Services\SocialLoginService;
use Illuminate\Support\Facades\Auth;
use Mhasnainjafri\RestApiKit\API;

class SocialLoginController extends Controller
{
    public function __construct(protected SocialLoginService $socialLoginService) {}

    public function login(SocialLoginRequest $request)
    {
        try {
            $provider = $request->input('provider'); // e.g. google.com, facebook.com
            $firebaseToken = $request->input('access_token'); // actually Firebase ID token

            // Authenticate via Firebase
            $user = $this->socialLoginService->authenticate($provider, $firebaseToken);

            // Log the user in (Laravel session, optional)
            Auth::login($user);

            // Return API token (Laravel Passport)
            return response()->json([
                'message' => 'Logged in successfully',
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken('API Token')->accessToken,
                ],
            ]);

        } catch (\Exception $e) {
            return API::error('Login failed: '.$e->getMessage());
        }
    }
}
