<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->middleware('auth');
        $this->google2fa = new Google2FA();
    }

    /**
     * Show 2FA settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('security.2fa.index', compact('user'));
    }

    /**
     * Enable 2FA and show QR code
     */
    public function enable()
    {
        $user = Auth::user();

        // Generate secret key if not exists
        if (!$user->two_factor_secret) {
            $secret = $this->google2fa->generateSecretKey();
            $user->two_factor_secret = encrypt($secret);
            $user->save();
        }

        $secret = decrypt($user->two_factor_secret);

        // Generate QR code
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($qrCodeUrl);

        return view('security.2fa.enable', compact('qrCode', 'secret'));
    }

    /**
     * Verify and confirm 2FA setup
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();
        $secret = decrypt($user->two_factor_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();

            $user->two_factor_enabled = true;
            $user->two_factor_confirmed_at = now();
            $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
            $user->save();

            return redirect()->route('profile.security.2fa.recovery-codes')
                ->with('success', '2FA enabled successfully! Please save your recovery codes.')
                ->with('recovery_codes', $recoveryCodes);
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('profile.security.2fa')
            ->with('success', '2FA has been disabled.');
    }

    /**
     * Show recovery codes
     */
    public function recoveryCodes()
    {
        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('profile.security.2fa')
                ->with('error', '2FA is not enabled.');
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        return view('security.2fa.recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->save();

        return back()
            ->with('success', 'Recovery codes regenerated successfully!')
            ->with('recovery_codes', $recoveryCodes);
    }

    /**
     * Verify 2FA during login
     */
    public function loginVerify(Request $request)
    {
        // Show form if GET request
        if ($request->isMethod('GET')) {
            return view('auth.2fa-verify');
        }

        $request->validate([
            'code' => 'required|string'
        ]);

        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = \App\Models\User::find(session('2fa:user:id'));

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Check if it's a recovery code
        if (strlen($request->code) > 6) {
            if ($this->verifyRecoveryCode($user, $request->code)) {
                Auth::login($user, session('2fa:remember', false));
                session()->forget(['2fa:user:id', '2fa:remember']);

                return redirect()->intended(route('feed.index'))
                    ->with('success', 'Login successful using recovery code!');
            }
        } else {
            // Regular 2FA code
            $secret = decrypt($user->two_factor_secret);
            $valid = $this->google2fa->verifyKey($secret, $request->code);

            if ($valid) {
                Auth::login($user, session('2fa:remember', false));
                session()->forget(['2fa:user:id', '2fa:remember']);

                return redirect()->intended(route('feed.index'))
                    ->with('success', 'Login successful!');
            }
        }

        return back()->withErrors(['code' => 'Invalid code. Please try again.']);
    }

    /**
     * Generate recovery codes
     */
    protected function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        }
        return $codes;
    }

    /**
     * Verify recovery code
     */
    protected function verifyRecoveryCode($user, $code)
    {
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (in_array($code, $recoveryCodes)) {
            // Remove used code
            $recoveryCodes = array_diff($recoveryCodes, [$code]);
            $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
            $user->save();

            return true;
        }

        return false;
    }
}
