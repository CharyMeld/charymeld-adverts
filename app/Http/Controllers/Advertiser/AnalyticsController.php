<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\AdImpression;
use App\Models\AdClick;
use App\Models\AdDailyStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Date range
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Get user's adverts
        $adverts = $user->adverts;

        // Overall stats
        $stats = [
            'total_campaigns' => $adverts->count(),
            'active_campaigns' => $adverts->where('is_active', true)->where('status', 'approved')->count(),
            'total_impressions' => $adverts->sum('impressions'),
            'total_clicks' => $adverts->sum('clicks'),
            'total_spent' => $adverts->sum('spent'),
            'average_ctr' => $adverts->avg('ctr') ?? 0,
        ];

        // Daily performance for chart
        $dailyStats = AdDailyStat::whereIn('advert_id', $adverts->pluck('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date',
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(cost) as cost'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing campaigns
        $topCampaigns = $adverts->sortByDesc('clicks')->take(5);

        // Device breakdown
        $deviceStats = AdImpression::whereIn('advert_id', $adverts->pluck('id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get();

        // Country breakdown
        $countryStats = AdImpression::whereIn('advert_id', $adverts->pluck('id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('country_code', DB::raw('COUNT(*) as count'))
            ->groupBy('country_code')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Hourly performance
        $hourlyStats = AdClick::whereIn('advert_id', $adverts->pluck('id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Recent activity
        $recentImpressions = AdImpression::whereIn('advert_id', $adverts->pluck('id'))
            ->with('advert')
            ->latest()
            ->limit(10)
            ->get();

        $recentClicks = AdClick::whereIn('advert_id', $adverts->pluck('id'))
            ->with('advert')
            ->latest()
            ->limit(10)
            ->get();

        return view('advertiser.analytics.index', compact(
            'stats',
            'dailyStats',
            'topCampaigns',
            'deviceStats',
            'countryStats',
            'hourlyStats',
            'recentImpressions',
            'recentClicks',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show campaign-specific analytics
     */
    public function showCampaign(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        // Date range
        $startDate = request()->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = request()->get('end_date', now()->format('Y-m-d'));

        // Campaign stats
        $stats = [
            'impressions' => $advert->impressions,
            'clicks' => $advert->clicks,
            'ctr' => $advert->ctr,
            'spent' => $advert->spent,
            'budget' => $advert->budget,
            'remaining_budget' => $advert->budget ? ($advert->budget - $advert->spent) : null,
            'average_cost_per_click' => $advert->clicks > 0 ? $advert->spent / $advert->clicks : 0,
        ];

        // Daily performance
        $dailyPerformance = AdDailyStat::where('advert_id', $advert->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        // Geographic distribution
        $geoDistribution = AdImpression::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('country_code', DB::raw('COUNT(*) as impressions'))
            ->groupBy('country_code')
            ->orderByDesc('impressions')
            ->get();

        // Device distribution
        $deviceDistribution = AdImpression::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('device_type', DB::raw('COUNT(*) as impressions'))
            ->groupBy('device_type')
            ->get();

        // Browser distribution
        $browserDistribution = AdImpression::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', DB::raw('COUNT(*) as impressions'))
            ->groupBy('browser')
            ->orderByDesc('impressions')
            ->limit(5)
            ->get();

        // Hourly clicks pattern
        $hourlyPattern = AdClick::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as clicks'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Publisher performance (if applicable)
        $publisherPerformance = AdDailyStat::where('advert_id', $advert->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('publisher_id')
            ->select('publisher_id',
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('SUM(clicks) as clicks'))
            ->groupBy('publisher_id')
            ->with('publisher')
            ->get();

        return view('advertiser.analytics.campaign', compact(
            'advert',
            'stats',
            'dailyPerformance',
            'geoDistribution',
            'deviceDistribution',
            'browserDistribution',
            'hourlyPattern',
            'publisherPerformance',
            'startDate',
            'endDate'
        ));
    }
}
