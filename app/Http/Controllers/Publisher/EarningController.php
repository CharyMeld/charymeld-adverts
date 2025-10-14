<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\PublisherProfile;
use App\Models\PublisherEarning;
use App\Models\PublisherPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarningController extends Controller
{
    /**
     * Display earnings overview
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Check if approved publisher
        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if (!$profile || !$profile->isApproved()) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Your publisher account must be approved first.');
        }

        // Date range filter
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Get earnings
        $earnings = PublisherEarning::where('publisher_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('advert', 'zone')
            ->orderBy('date', 'desc')
            ->paginate(50);

        // Summary stats
        $summary = [
            'total' => PublisherEarning::where('publisher_id', $user->id)->sum('amount'),
            'pending' => PublisherEarning::where('publisher_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'paid' => PublisherEarning::where('publisher_id', $user->id)
                ->where('status', 'paid')
                ->sum('amount'),
            'period_total' => $earnings->sum('amount'),
        ];

        // Daily breakdown
        $dailyEarnings = PublisherEarning::where('publisher_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date', DB::raw('SUM(amount) as total'), DB::raw('SUM(clicks) as clicks'), DB::raw('SUM(impressions) as impressions'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing zones
        $topZones = PublisherEarning::where('publisher_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('zone_id', DB::raw('SUM(amount) as total'))
            ->groupBy('zone_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('zone')
            ->get();

        return view('publisher.earnings.index', compact(
            'earnings',
            'summary',
            'dailyEarnings',
            'topZones',
            'startDate',
            'endDate',
            'profile'
        ));
    }

    /**
     * Show payout requests
     */
    public function payouts()
    {
        $user = auth()->user();

        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if (!$profile || !$profile->isApproved()) {
            return redirect()->route('publisher.dashboard');
        }

        // Get pending balance
        $pendingBalance = PublisherEarning::where('publisher_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Get payout history
        $payouts = PublisherPayout::where('publisher_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Check if can request payout
        $canRequestPayout = $pendingBalance >= $profile->minimum_payout;

        return view('publisher.earnings.payouts', compact(
            'profile',
            'pendingBalance',
            'payouts',
            'canRequestPayout'
        ));
    }

    /**
     * Request payout
     */
    public function requestPayout(Request $request)
    {
        $user = auth()->user();

        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if (!$profile || !$profile->isApproved()) {
            return back()->with('error', 'Unauthorized');
        }

        // Calculate available balance
        $availableBalance = PublisherEarning::where('publisher_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Check minimum payout
        if ($availableBalance < $profile->minimum_payout) {
            return back()->with('error', "Minimum payout amount is {$profile->minimum_payout}. Your current balance is {$availableBalance}.");
        }

        // Check for pending payout
        $pendingPayout = PublisherPayout::where('publisher_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingPayout) {
            return back()->with('error', 'You already have a pending payout request.');
        }

        // Create payout request
        $payout = PublisherPayout::create([
            'publisher_id' => $user->id,
            'amount' => $availableBalance,
            'payment_method' => $profile->payment_method,
            'payment_details' => $profile->payment_details,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // Mark earnings as processing
        PublisherEarning::where('publisher_id', $user->id)
            ->where('status', 'pending')
            ->update(['payout_id' => $payout->id]);

        // Create admin notification
        \App\Models\AdminNotification::createPayoutRequest(
            $payout->id,
            $user->id,
            $availableBalance
        );

        return back()->with('success', "Payout request for ₦" . number_format($availableBalance, 2) . " submitted successfully!");
    }

    /**
     * Export earnings to CSV
     */
    public function exportCsv(Request $request)
    {
        $user = auth()->user();

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $earnings = PublisherEarning::where('publisher_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('advert', 'zone')
            ->orderBy('date', 'desc')
            ->get();

        $filename = "earnings_{$startDate}_to_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($earnings) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Date', 'Ad Title', 'Zone', 'Impressions', 'Clicks', 'Amount', 'Status']);

            // Data
            foreach ($earnings as $earning) {
                fputcsv($file, [
                    $earning->date,
                    $earning->advert->title ?? 'N/A',
                    $earning->zone->zone_name ?? 'N/A',
                    $earning->impressions,
                    $earning->clicks,
                    number_format($earning->amount, 2),
                    $earning->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
