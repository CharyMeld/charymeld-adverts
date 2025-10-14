<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\PublisherZone;
use App\Services\AdTrackingService;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected $trackingService;
    protected $fraudService;

    public function __construct(AdTrackingService $trackingService, FraudDetectionService $fraudService)
    {
        $this->trackingService = $trackingService;
        $this->fraudService = $fraudService;
    }

    /**
     * Serve ads for a publisher zone
     * GET /api/ad/serve?zone=ABC123&count=3
     */
    public function serve(Request $request)
    {
        $zoneCode = $request->get('zone');
        $count = min((int) $request->get('count', 1), 10); // Max 10 ads

        // Validate zone
        $zone = null;
        if ($zoneCode) {
            $zone = PublisherZone::where('zone_code', $zoneCode)
                ->where('is_active', true)
                ->first();

            if (!$zone) {
                return response()->json(['error' => 'Invalid zone'], 404);
            }
        }

        // Get matching ads
        $ads = $this->trackingService->getMatchingAds($zoneCode, $count);

        if (empty($ads)) {
            return response()->json(['ads' => []]);
        }

        // Format ads for display
        $formattedAds = [];
        foreach ($ads as $ad) {
            // Record impression
            $impression = $this->trackingService->recordImpression(
                $ad,
                $zone->publisher_id ?? null,
                $request->header('referer')
            );

            if ($impression) {
                $formattedAds[] = [
                    'id' => $ad->id,
                    'impression_id' => $impression->id,
                    'type' => $ad->ad_type,
                    'title' => $ad->title,
                    'description' => $ad->description,
                    'image' => $ad->banner_image ? asset('storage/' . $ad->banner_image) : ($ad->primaryImage ? asset('storage/' . $ad->primaryImage->image_path) : null),
                    'url' => route('api.ad.click', ['id' => $ad->id, 'impression' => $impression->id]),
                    'destination' => $ad->banner_url ?? route('advert.show', $ad->slug),
                    'size' => $ad->banner_size,
                ];
            }
        }

        return response()->json([
            'ads' => $formattedAds,
            'zone' => $zoneCode,
            'count' => count($formattedAds),
        ]);
    }

    /**
     * Track ad click and redirect
     * GET /api/ad/click/{id}?impression={impression_id}
     */
    public function click(Request $request, $id)
    {
        $advert = Advert::find($id);

        if (!$advert) {
            return response()->json(['error' => 'Ad not found'], 404);
        }

        $impressionId = $request->get('impression');
        $publisherId = null;

        // Get publisher from impression
        if ($impressionId) {
            $impression = \App\Models\AdImpression::find($impressionId);
            $publisherId = $impression->publisher_id ?? null;
        }

        // Record click
        $click = $this->trackingService->recordClick($advert, $impressionId, $publisherId);

        if ($click) {
            // Valid click - redirect
            return redirect()->away($click->destination_url);
        } else {
            // Fraud detected or budget exhausted
            return response()->json([
                'error' => 'Click could not be processed',
                'reason' => 'Fraud detection or budget limit reached'
            ], 403);
        }
    }

    /**
     * Track impression via pixel (alternative method)
     * GET /api/ad/impression/{id}
     */
    public function trackImpression(Request $request, $id)
    {
        $advert = Advert::find($id);

        if (!$advert) {
            return response()->json(['error' => 'Ad not found'], 404);
        }

        $impression = $this->trackingService->recordImpression(
            $advert,
            null,
            $request->header('referer')
        );

        if ($impression) {
            // Return 1x1 transparent pixel
            return response()->make(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'), 200)
                ->header('Content-Type', 'image/gif')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return response()->json(['error' => 'Could not track'], 400);
    }

    /**
     * Get ad statistics
     * GET /api/ad/stats/{id}
     */
    public function stats($id)
    {
        $advert = Advert::with('dailyStats')->find($id);

        if (!$advert || $advert->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'id' => $advert->id,
            'title' => $advert->title,
            'status' => $advert->status,
            'is_active' => $advert->is_active,
            'is_paused' => $advert->is_paused,
            'impressions' => $advert->impressions,
            'clicks' => $advert->clicks,
            'ctr' => $advert->ctr,
            'budget' => $advert->budget,
            'spent' => $advert->spent,
            'remaining' => $advert->budget ? ($advert->budget - $advert->spent) : null,
            'daily_stats' => $advert->dailyStats()->orderBy('date', 'desc')->take(30)->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Get embed code for a zone
     * GET /api/ad/embed/{zoneCode}
     */
    public function embed($zoneCode)
    {
        $zone = PublisherZone::where('zone_code', $zoneCode)->first();

        if (!$zone) {
            return response()->json(['error' => 'Zone not found'], 404);
        }

        $embedUrl = route('api.ad.serve', ['zone' => $zoneCode]);
        $scriptUrl = asset('js/ad-widget.js');

        $embedCode = <<<HTML
<!-- CharyMeld Ad Zone: {$zone->zone_name} -->
<div id="charymeld-ad-{$zoneCode}" data-zone="{$zoneCode}" data-size="{$zone->size}"></div>
<script src="{$scriptUrl}" async></script>
HTML;

        return response()->json([
            'zone_code' => $zoneCode,
            'zone_name' => $zone->zone_name,
            'embed_code' => $embedCode,
            'api_url' => $embedUrl,
        ]);
    }
}
