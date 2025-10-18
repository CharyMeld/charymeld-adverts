<?php

namespace App\Http\Controllers;

use App\Mail\PartnerApplicationReceived;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    /**
     * Show the partners page
     */
    public function index()
    {
        return view('pages.partners');
    }

    /**
     * Submit partner application
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'partnership_type' => 'required|in:affiliate,strategic,media',
            'message' => 'required|string|min:50',
        ]);

        // Send notification email to admin
        // Mail::to(config('mail.from.address'))->send(new PartnerApplicationReceived($validated));

        // Optionally create a referral code for approved partners
        // This can be done manually by admin or automated

        return back()->with('success', 'Thank you for your application! We will review it and get back to you within 2-3 business days.');
    }

    /**
     * Show press/media page
     */
    public function press()
    {
        return view('pages.press');
    }

    /**
     * Generate referral link for authenticated user
     */
    public function generateReferralLink(Request $request)
    {
        $user = $request->user();

        // Check if user already has a referral code
        $referral = Referral::where('referrer_id', $user->id)
            ->where('source', 'user')
            ->first();

        if (!$referral) {
            $code = Referral::generateCode();
            $referral = Referral::create([
                'referrer_id' => $user->id,
                'referral_code' => $code,
                'source' => 'user',
                'status' => 'active',
            ]);
        }

        return response()->json([
            'code' => $referral->referral_code,
            'link' => route('home') . '?ref=' . $referral->referral_code,
        ]);
    }

    /**
     * Show referral dashboard
     */
    public function referralDashboard(Request $request)
    {
        $user = $request->user();

        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_clicks' => Referral::where('referrer_id', $user->id)
                ->whereNotNull('clicked_at')
                ->count(),
            'total_registrations' => Referral::where('referrer_id', $user->id)
                ->whereNotNull('registered_at')
                ->count(),
            'total_conversions' => Referral::where('referrer_id', $user->id)
                ->whereNotNull('converted_at')
                ->count(),
            'total_earnings' => Referral::where('referrer_id', $user->id)
                ->sum('commission_earned'),
        ];

        // Get or create referral code
        $referralCode = Referral::where('referrer_id', $user->id)
            ->where('source', 'user')
            ->value('referral_code');

        if (!$referralCode) {
            $code = Referral::generateCode();
            Referral::create([
                'referrer_id' => $user->id,
                'referral_code' => $code,
                'source' => 'user',
                'status' => 'active',
            ]);
            $referralCode = $code;
        }

        return view('referrals.dashboard', compact('referrals', 'stats', 'referralCode'));
    }
}
