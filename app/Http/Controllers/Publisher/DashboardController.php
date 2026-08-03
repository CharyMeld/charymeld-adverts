<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\PublisherProfile;
use App\Models\PublisherZone;
use App\Models\PublisherEarning;
use App\Models\AdClick;
use App\Models\AdImpression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display publisher dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get or redirect to registration if no profile
        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect()->route('publisher.register')
                ->with('info', 'Please complete your publisher registration first.');
        }

        if (!$profile->isApproved()) {
            return view('publisher.pending', compact('profile'));
        }

        // Get zones
        $zones = PublisherZone::where('publisher_id', $user->id)
            ->withCount(['earnings'])
            ->get();

        // Get earnings summary
        $totalEarnings = PublisherEarning::where('publisher_id', $user->id)->sum('amount');
        $pendingEarnings = PublisherEarning::where('publisher_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
        $paidEarnings = PublisherEarning::where('publisher_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        // Get today's stats
        $todayImpressions = AdImpression::where('publisher_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $todayClicks = AdClick::where('publisher_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $todayEarnings = PublisherEarning::where('publisher_id', $user->id)
            ->whereDate('date', today())
            ->sum('amount');

        // Get last 30 days earnings chart data
        $earningsChart = PublisherEarning::where('publisher_id', $user->id)
            ->where('date', '>=', now()->subDays(30))
            ->select('date', DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent earnings
        $recentEarnings = PublisherEarning::where('publisher_id', $user->id)
            ->with('advert')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('publisher.dashboard', compact(
            'profile',
            'zones',
            'totalEarnings',
            'pendingEarnings',
            'paidEarnings',
            'todayImpressions',
            'todayClicks',
            'todayEarnings',
            'earningsChart',
            'recentEarnings'
        ));
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        $user = auth()->user();

        // Check if already registered
        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if ($profile) {
            return redirect()->route('publisher.dashboard');
        }

        return view('publisher.register');
    }

    /**
     * Register as publisher
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'website_url' => 'required|url|unique:publisher_profiles,website_url',
            'website_name' => 'required|string|max:255',
            'website_description' => 'required|string|max:1000',
            'website_category' => 'required|string|in:blog,news,ecommerce,entertainment,technology,education,health,finance,sports,other',
            'monthly_visitors' => 'required|integer|min:0',
            'payment_method' => 'required|string|in:bank_transfer,paypal,payoneer,mobile_money',
            'payment_details' => 'required|array',
        ]);

        // Create publisher profile
        $profile = PublisherProfile::create([
            'user_id' => auth()->id(),
            'website_url' => $validated['website_url'],
            'website_name' => $validated['website_name'],
            'website_description' => $validated['website_description'],
            'website_category' => $validated['website_category'],
            'monthly_visitors' => $validated['monthly_visitors'],
            'status' => 'pending',
            'revenue_share' => 70.00, // Default 70% to publisher
            'payment_method' => $validated['payment_method'],
            'payment_details' => $validated['payment_details'],
            'minimum_payout' => 50.00, // Default minimum payout
        ]);

        // Update user type
        auth()->user()->update(['user_type' => 'publisher']);

        return redirect()->route('publisher.dashboard')
            ->with('success', 'Your publisher application has been submitted for review.');
    }

    /**
     * Update publisher profile
     */
    public function updateProfile(Request $request)
    {
        $profile = PublisherProfile::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'website_url' => 'required|url|unique:publisher_profiles,website_url,' . $profile->id,
            'website_name' => 'required|string|max:255',
            'website_description' => 'required|string|max:1000',
            'website_category' => 'required|string',
            'monthly_visitors' => 'required|integer|min:0',
            'payment_method' => 'required|string',
            'payment_details' => 'required|array',
        ]);

        $profile->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
