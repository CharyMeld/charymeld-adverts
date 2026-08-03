<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\AdvertAnalytic;
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

        // Get user's adverts with analytics
        $adverts = $user->adverts()->with('analytics')->get();

        // Calculate overall stats from advert_analytics table
        $advertIds = $adverts->pluck('id');

        $analyticsData = AdvertAnalytic::whereIn('advert_id', $advertIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                SUM(impressions) as total_impressions,
                SUM(clicks) as total_clicks,
                SUM(contact_clicks) as total_contact_clicks,
                SUM(unique_visitors) as total_unique_visitors
            ')
            ->first();

        $totalImpressions = $analyticsData->total_impressions ?? 0;
        $totalClicks = $analyticsData->total_clicks ?? 0;

        // Overall stats
        $stats = [
            'total_campaigns' => $adverts->count(),
            'active_campaigns' => $adverts->where('is_active', true)->where('status', 'approved')->count(),
            'total_impressions' => $totalImpressions,
            'total_clicks' => $totalClicks,
            'total_contact_clicks' => $analyticsData->total_contact_clicks ?? 0,
            'average_ctr' => $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0,
        ];

        // Daily performance for chart
        $dailyStats = AdvertAnalytic::whereIn('advert_id', $advertIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date',
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(contact_clicks) as contact_clicks'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing campaigns
        $topCampaigns = [];
        foreach ($adverts as $advert) {
            $advertStats = AdvertAnalytic::getStats($advert->id, $startDate, $endDate);
            $advert->analytics_stats = $advertStats;
            $topCampaigns[] = $advert;
        }
        $topCampaigns = collect($topCampaigns)->sortByDesc('analytics_stats.total_clicks')->take(5);

        return view('advertiser.analytics.index', compact(
            'stats',
            'dailyStats',
            'topCampaigns',
            'adverts',
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

        // Get analytics stats
        $analyticsStats = AdvertAnalytic::getStats($advert->id, $startDate, $endDate);

        // Campaign stats
        $stats = [
            'impressions' => $analyticsStats['total_impressions'],
            'clicks' => $analyticsStats['total_clicks'],
            'contact_clicks' => $analyticsStats['total_contact_clicks'],
            'unique_visitors' => $analyticsStats['total_unique_visitors'],
            'ctr' => $analyticsStats['ctr'],
        ];

        // Daily performance for chart
        $dailyPerformance = AdvertAnalytic::getDailyStats($advert->id, 30);

        // Improvement suggestions based on performance
        $suggestions = $this->generateSuggestions($stats, $advert);

        return view('advertiser.analytics.campaign', compact(
            'advert',
            'stats',
            'dailyPerformance',
            'suggestions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate improvement suggestions based on ad performance
     */
    private function generateSuggestions($stats, $advert)
    {
        $suggestions = [];

        // Low CTR suggestions
        if ($stats['ctr'] < 1) {
            $suggestions[] = [
                'type' => 'warning',
                'title' => 'Low Click-Through Rate',
                'message' => 'Your CTR is below 1%. Consider improving your ad title and images to attract more clicks.',
                'icon' => '⚠️'
            ];
        }

        // Low impressions
        if ($stats['impressions'] < 100) {
            $suggestions[] = [
                'type' => 'info',
                'title' => 'Increase Visibility',
                'message' => 'Your ad has low impressions. Consider boosting your ad or improving your title with better keywords.',
                'icon' => '👁️'
            ];
        }

        // No contact clicks
        if ($stats['clicks'] > 20 && $stats['contact_clicks'] == 0) {
            $suggestions[] = [
                'type' => 'warning',
                'title' => 'No Contact Engagement',
                'message' => 'People are viewing your ad but not contacting you. Make your contact information more prominent.',
                'icon' => '📞'
            ];
        }

        // Good performance
        if ($stats['ctr'] > 3) {
            $suggestions[] = [
                'type' => 'success',
                'title' => 'Great Performance!',
                'message' => 'Your ad has a strong CTR above 3%. Keep up the good work!',
                'icon' => '🎉'
            ];
        }

        // Missing images
        if (!$advert->primaryImage) {
            $suggestions[] = [
                'type' => 'danger',
                'title' => 'Add Images',
                'message' => 'Ads with images get 5x more engagement. Add high-quality images to your ad.',
                'icon' => '📷'
            ];
        }

        return $suggestions;
    }
}
