<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\AdDailyStat;
use App\Models\AdImpression;
use App\Models\AdClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Export campaign report as PDF
     */
    public function exportCampaignPdf(Request $request, Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Collect data
        $stats = [
            'impressions' => $advert->impressions,
            'clicks' => $advert->clicks,
            'ctr' => $advert->ctr,
            'spent' => $advert->spent,
            'budget' => $advert->budget,
            'remaining_budget' => $advert->budget ? ($advert->budget - $advert->spent) : null,
            'average_cost_per_click' => $advert->clicks > 0 ? $advert->spent / $advert->clicks : 0,
        ];

        $dailyPerformance = AdDailyStat::where('advert_id', $advert->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $geoDistribution = AdImpression::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('country_code', DB::raw('COUNT(*) as impressions'))
            ->groupBy('country_code')
            ->orderByDesc('impressions')
            ->limit(10)
            ->get();

        $deviceDistribution = AdImpression::where('advert_id', $advert->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('device_type', DB::raw('COUNT(*) as impressions'))
            ->groupBy('device_type')
            ->get();

        $data = [
            'advert' => $advert,
            'stats' => $stats,
            'dailyPerformance' => $dailyPerformance,
            'geoDistribution' => $geoDistribution,
            'deviceDistribution' => $deviceDistribution,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->format('F j, Y g:i A'),
        ];

        $pdf = Pdf::loadView('advertiser.reports.campaign-pdf', $data);

        return $pdf->download('campaign-report-' . $advert->slug . '-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export campaign report as CSV
     */
    public function exportCampaignCsv(Request $request, Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $dailyPerformance = AdDailyStat::where('advert_id', $advert->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $filename = 'campaign-report-' . $advert->slug . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($advert, $dailyPerformance) {
            $file = fopen('php://output', 'w');

            // Campaign Summary
            fputcsv($file, ['Campaign Report']);
            fputcsv($file, ['Campaign', $advert->title]);
            fputcsv($file, ['Status', $advert->status]);
            fputcsv($file, ['Budget', number_format($advert->budget ?? 0, 2)]);
            fputcsv($file, ['Spent', number_format($advert->spent, 2)]);
            fputcsv($file, ['Total Impressions', number_format($advert->impressions)]);
            fputcsv($file, ['Total Clicks', number_format($advert->clicks)]);
            fputcsv($file, ['CTR', number_format($advert->ctr, 2) . '%']);
            fputcsv($file, []);

            // Daily Performance
            fputcsv($file, ['Daily Performance']);
            fputcsv($file, ['Date', 'Impressions', 'Clicks', 'CTR (%)', 'Cost']);

            foreach ($dailyPerformance as $day) {
                $ctr = $day->impressions > 0 ? ($day->clicks / $day->impressions) * 100 : 0;
                fputcsv($file, [
                    $day->date,
                    $day->impressions,
                    $day->clicks,
                    number_format($ctr, 2),
                    number_format($day->cost, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all campaigns overview as PDF
     */
    public function exportAllCampaignsPdf(Request $request)
    {
        $user = auth()->user();

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $adverts = $user->adverts;

        $stats = [
            'total_campaigns' => $adverts->count(),
            'active_campaigns' => $adverts->where('is_active', true)->where('status', 'approved')->count(),
            'total_impressions' => $adverts->sum('impressions'),
            'total_clicks' => $adverts->sum('clicks'),
            'total_spent' => $adverts->sum('spent'),
            'average_ctr' => $adverts->avg('ctr') ?? 0,
        ];

        $dailyStats = AdDailyStat::whereIn('advert_id', $adverts->pluck('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date',
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(cost) as cost'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCampaigns = $adverts->sortByDesc('clicks')->take(10);

        $data = [
            'user' => $user,
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'topCampaigns' => $topCampaigns,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->format('F j, Y g:i A'),
        ];

        $pdf = Pdf::loadView('advertiser.reports.all-campaigns-pdf', $data);

        return $pdf->download('all-campaigns-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export all campaigns overview as CSV
     */
    public function exportAllCampaignsCsv(Request $request)
    {
        $user = auth()->user();

        $adverts = $user->adverts;

        $filename = 'all-campaigns-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($adverts) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Campaign', 'Type', 'Status', 'Impressions', 'Clicks', 'CTR (%)', 'Budget', 'Spent', 'Created']);

            // Data
            foreach ($adverts as $advert) {
                fputcsv($file, [
                    $advert->title,
                    $advert->ad_type ?? 'classified',
                    $advert->status,
                    number_format($advert->impressions),
                    number_format($advert->clicks),
                    number_format($advert->ctr, 2),
                    number_format($advert->budget ?? 0, 2),
                    number_format($advert->spent, 2),
                    $advert->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
