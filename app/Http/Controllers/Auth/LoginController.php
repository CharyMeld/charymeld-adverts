<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First, verify credentials without logging in
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !Auth::getProvider()->validateCredentials($user, $credentials)) {
            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        // Check if user has 2FA enabled
        if ($user->two_factor_enabled) {
            // Store user ID and remember preference in session
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:remember', $request->boolean('remember'));

            // Redirect to 2FA verification page
            return redirect()->route('login.2fa')
                ->with('message', 'Please enter your 2FA code to continue.');
        }

        // Normal login without 2FA
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect all authenticated users to Feed by default
            return redirect()->intended(route('feed.index'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    public function logout(Request $request)
    {
        // Clear all guards
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Clear session data
        $request->session()->flush();

        return redirect('/')->with('status', 'You have been successfully logged out.');
    }
}
