<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\PublisherZone;
use App\Models\PublisherProfile;
use App\Models\AdImpression;
use App\Models\AdClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    /**
     * Display all zones
     */
    public function index()
    {
        $user = auth()->user();

        // Check if approved publisher
        $profile = PublisherProfile::where('user_id', $user->id)->first();

        if (!$profile || !$profile->isApproved()) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Your publisher account must be approved first.');
        }

        // Get zones with stats
        $zones = PublisherZone::where('publisher_id', $user->id)
            ->withCount([
                'earnings as total_earnings' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(amount), 0)'));
                }
            ])
            ->get();

        return view('publisher.zones.index', compact('zones', 'profile'));
    }

    /**
     * Show create zone form
     */
    public function create()
    {
        $profile = PublisherProfile::where('user_id', auth()->id())->first();

        if (!$profile || !$profile->isApproved()) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Your publisher account must be approved first.');
        }

        return view('publisher.zones.create');
    }

    /**
     * Store new zone
     */
    public function store(Request $request)
    {
        $profile = PublisherProfile::where('user_id', auth()->id())->first();

        if (!$profile || !$profile->isApproved()) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Your publisher account must be approved first.');
        }

        $validated = $request->validate([
            'zone_name' => 'required|string|max:255',
            'ad_type' => 'required|string|in:banner,text,video,any',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $zone = PublisherZone::create([
            'publisher_id' => auth()->id(),
            'zone_name' => $validated['zone_name'],
            'ad_type' => $validated['ad_type'],
            'size' => $validated['size'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('publisher.zones.show', $zone)
            ->with('success', 'Ad zone created successfully!');
    }

    /**
     * Show zone details with embed code
     */
    public function show(PublisherZone $zone)
    {
        // Check ownership
        if ($zone->publisher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Get zone stats
        $stats = [
            'total_impressions' => $zone->impressions,
            'total_clicks' => $zone->clicks,
            'ctr' => $zone->getCTR(),
            'today_impressions' => AdImpression::where('publisher_id', $zone->publisher_id)
                ->whereDate('created_at', today())
                ->count(),
            'today_clicks' => AdClick::where('publisher_id', $zone->publisher_id)
                ->whereDate('created_at', today())
                ->count(),
        ];

        // Get earnings
        $totalEarnings = $zone->earnings()->sum('amount');
        $todayEarnings = $zone->earnings()->whereDate('date', today())->sum('amount');

        // Get last 7 days performance
        $performance = AdImpression::where('publisher_id', $zone->publisher_id)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Generate embed code
        $embedCode = $this->generateEmbedCode($zone);

        return view('publisher.zones.show', compact(
            'zone',
            'stats',
            'totalEarnings',
            'todayEarnings',
            'performance',
            'embedCode'
        ));
    }

    /**
     * Show edit form
     */
    public function edit(PublisherZone $zone)
    {
        // Check ownership
        if ($zone->publisher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('publisher.zones.edit', compact('zone'));
    }

    /**
     * Update zone
     */
    public function update(Request $request, PublisherZone $zone)
    {
        // Check ownership
        if ($zone->publisher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'zone_name' => 'required|string|max:255',
            'ad_type' => 'required|string|in:banner,text,video,any',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $zone->update($validated);

        return redirect()->route('publisher.zones.show', $zone)
            ->with('success', 'Zone updated successfully.');
    }

    /**
     * Delete zone
     */
    public function destroy(PublisherZone $zone)
    {
        // Check ownership
        if ($zone->publisher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $zone->delete();

        return redirect()->route('publisher.zones.index')
            ->with('success', 'Zone deleted successfully.');
    }

    /**
     * Toggle zone active status
     */
    public function toggleStatus(PublisherZone $zone)
    {
        // Check ownership
        if ($zone->publisher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $zone->update(['is_active' => !$zone->is_active]);

        return back()->with('success', 'Zone status updated.');
    }

    /**
     * Generate embed code for zone
     */
    protected function generateEmbedCode(PublisherZone $zone): array
    {
        $scriptUrl = asset('js/ad-widget.js');
        $zoneCode = $zone->zone_code;

        // Basic embed code
        $basic = <<<HTML
<!-- CharyMeld Ad Zone: {$zone->zone_name} -->
<div id="charymeld-ad-{$zoneCode}" data-zone="{$zoneCode}" data-size="{$zone->size}"></div>
<script src="{$scriptUrl}" async></script>
HTML;

        // Advanced embed with multiple ads
        $advanced = <<<HTML
<!-- CharyMeld Ad Zone: {$zone->zone_name} (Multiple Ads) -->
<div id="charymeld-ad-{$zoneCode}" data-zone="{$zoneCode}" data-size="{$zone->size}" data-count="3"></div>
<script src="{$scriptUrl}" async></script>
HTML;

        // Manual JavaScript initialization
        $manual = <<<HTML
<!-- CharyMeld Ad Zone: {$zone->zone_name} (Manual Init) -->
<div id="ad-container-{$zoneCode}"></div>
<script src="{$scriptUrl}"></script>
<script>
  CharyMeldAds.load('{$zoneCode}', 'ad-container-{$zoneCode}', 1);
</script>
HTML;

        return [
            'basic' => $basic,
            'advanced' => $advanced,
            'manual' => $manual,
            'api_url' => route('api.ad.serve', ['zone' => $zoneCode]),
        ];
    }
}
