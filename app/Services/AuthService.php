<?php

namespace App\Services;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(Request $request)
    {
        $profile_photo = $request->file('profile_picture')?->store('profile_pictures', 'public');
        $user = User::create([
            'name' => $request->name ?? '',
            'username' => $request->username ?? null,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password), // temporary random
            'profile_photo' => $profile_photo ?? null,
        ]);

        $user->assignRole($request->role ?? 'guide');

        return $this->sendOtp($user->email);
    }

    public function sendOtp($email)
    {
        // check if user has already sent 3 OTP requests
        $counter = Cache::get('otp_count_'.$email, 0);
        if ($counter >= Config::get('travelly.otp_max_attempts')) {
            return response()->json(['message' => 'Maximum OTP attempts reached'], 429);
        }
        // assume authenticated user
        $user = User::where('email', $email)->firstOrFail();
        $otp = rand(1000, 9999);
        Cache::put('otp_'.$user->email, $otp, now()->addMinutes(10));

        // increment counter
        Cache::increment('otp_count_'.$email);

        // send OTP to user's email

        if (config('app.debug')) {
            return ['message' => 'Otp has been sent ', 'otp' => $otp];
        } else {
            return ['message' => 'OTP sent.', 'otp' => null];
        }
    }

    public function verifyOtp(Request $request)
    {
        $otp = Cache::get('otp_'.$request->email);
        if ($otp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // login user
        Auth::login($user);

        // generate token
        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token,
            'roles' => $user->getRoleNames()->first(),
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {   
        try {
            $user = Auth::user();

            // Fields to update directly on the User model
            $data = $request->only(['name','username',
                'description', 'facebook', 'tiktok', 'linkedin',
                'local_expert_google_place_id', 'local_expert_place_name',
            ]);

            // Handle video upload
            if ($request->hasFile('short_video')) {
                $data['short_video'] = $request->file('short_video')->store('videos', 'public');
            }
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('profile_photo', 'public');
            }

            // Update user fields (only non-null values)
            $user->update(array_filter($data, fn ($value) => ! is_null($value)));

            // Sync relationships (using sync to handle empty arrays as well)
            $user->interests()->sync($request->my_interests_ids ?? []);
            $user->buddyInterests()->sync($request->buddy_interests_ids ?? []);
            $user->travelActivities()->sync($request->travel_activities_ids ?? []);
            $user->travelWithOptions()->sync($request->travel_with_options_ids ?? []);

            // Load relationships for the response
            $user->load(['interests', 'buddyInterests', 'travelActivities', 'travelWithOptions']);

            return response()->json([
                'message' => 'Profile updated successfully',
                'profile' => $user,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to update profile due to a database error.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred while updating the profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
