<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Redirect to the provider
     */
    public function redirect($provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     */
    public function callback($provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login using ' . ucfirst($provider) . '. Please try again.');
        }

        // Find or create user
        $user = $this->findOrCreateUser($socialUser, $provider);

        // Login the user
        Auth::login($user, true);

        return redirect()->intended(route('home'))->with('success', 'Successfully logged in!');
    }

    /**
     * Find or create a user
     */
    protected function findOrCreateUser($socialUser, $provider)
    {
        // Check if user already exists with this email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update social ID if not set
            $this->updateSocialId($user, $provider, $socialUser->getId());
            return $user;
        }

        // Create new user
        return User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(24)), // Random password
            'avatar' => $socialUser->getAvatar(),
            'role' => 'advertiser', // Default role
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verify social login users
            $provider . '_id' => $socialUser->getId(),
        ]);
    }

    /**
     * Update social ID for existing user
     */
    protected function updateSocialId($user, $provider, $socialId)
    {
        $column = $provider . '_id';

        // Check if column exists in users table
        if (!$user->{$column}) {
            $user->{$column} = $socialId;
            $user->save();
        }
    }

    /**
     * Validate the provider
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'twitter'])) {
            abort(404);
        }
    }
}
