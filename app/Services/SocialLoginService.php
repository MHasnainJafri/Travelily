<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Kreait\Firebase\Auth\UserRecord;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;

class SocialLoginService
{
    protected $auth;

    public function __construct()
    {
        $this->auth = (new Factory)
            ->withServiceAccount(storage_path('firebase/firebase-adminsdk.json'))
            ->createAuth();
    }

    /**
     * Authenticate Firebase ID token and return or create a User
     */
    public function authenticate(string $provider, string $firebaseIdToken): User
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($firebaseIdToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $firebaseUser = $this->auth->getUser($uid);

            return $this->findOrCreateUserFromFirebase($firebaseUser, $provider);
        } catch (FailedToVerifyToken $e) {
            throw new Exception('Invalid Firebase token: '.$e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Authentication failed: '.$e->getMessage());
        }
    }

    /**
     * Create or return existing user from Firebase user object
     */
    protected function findOrCreateUserFromFirebase(UserRecord $firebaseUser, string $provider): User
    {
        $email = $firebaseUser->email ?? null;
        $name = $firebaseUser->displayName ?? 'Firebase User';
        $providerId = $firebaseUser->uid;

        // Check if social account already linked
        $linkedSocialAccount = SocialAccount::query()
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if ($linkedSocialAccount) {
            $user = $linkedSocialAccount->user;
            $user->reg_type = 'old';

            return $user;
        }

        // Check if a user with the email already exists
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt(str()->random(16)),
            ]);
            $user->markEmailAsVerified();
            $user->reg_type = 'new';
        } else {
            $user->reg_type = 'old';
        }

        // Link social account for future logins
        $user->linkedSocialAccounts()->create([
            'provider_id' => $providerId,
            'provider' => $provider,
        ]);

        return $user;
    }
}
