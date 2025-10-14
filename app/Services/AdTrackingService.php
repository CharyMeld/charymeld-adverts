<?php

namespace App\Services;

use App\Models\Advert;
use App\Models\AdImpression;
use App\Models\AdClick;
use App\Models\AdDailyStat;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class AdTrackingService
{
    protected $fraudDetectionService;

    public function __construct(FraudDetectionService $fraudDetectionService)
    {
        $this->fraudDetectionService = $fraudDetectionService;
    }

    /**
     * Record an ad impression (view)
     */
    public function recordImpression(Advert $advert, ?int $publisherId = null, ?string $pageUrl = null): ?AdImpression
    {
        $visitorData = $this->getVisitorData();

        // Check fraud first
        if ($this->fraudDetectionService->isBlacklisted($visitorData['ip_address'])) {
            return null;
        }

        if ($this->fraudDetectionService->isBot($visitorData['user_agent'])) {
            return null;
        }

        // Record impression
        $impression = AdImpression::create([
            'advert_id' => $advert->id,
            'publisher_id' => $publisherId,
            'ip_address' => $visitorData['ip_address'],
            'user_agent' => $visitorData['user_agent'],
            'country_code' => $visitorData['country_code'],
            'device_type' => $visitorData['device_type'],
            'browser' => $visitorData['browser'],
            'os' => $visitorData['os'],
            'referrer' => $visitorData['referrer'],
            'page_url' => $pageUrl ?? $visitorData['referrer'],
            'created_at' => now(),
        ]);

        // Update advert counters
        $advert->increment('impressions');
        $advert->updateCTR();

        // Update daily stats
        $this->updateDailyStats($advert->id, 'impression', $publisherId);

        return $impression;
    }

    /**
     * Record an ad click
     */
    public function recordClick(Advert $advert, ?int $impressionId = null, ?int $publisherId = null): ?AdClick
    {
        $visitorData = $this->getVisitorData();

        // Fraud detection
        if ($this->fraudDetectionService->isBlacklisted($visitorData['ip_address'])) {
            $this->fraudDetectionService->logFraud($advert->id, null, 'duplicate_ip', 'IP is blacklisted');
            return null;
        }

        if ($this->fraudDetectionService->isBot($visitorData['user_agent'])) {
            $this->fraudDetectionService->logFraud($advert->id, null, 'bot_detected', 'Bot user agent');
            return null;
        }

        // Check rate limit
        if (!$this->fraudDetectionService->checkRateLimit($visitorData['ip_address'], $advert->id)) {
            $this->fraudDetectionService->logFraud($advert->id, null, 'rapid_clicks', 'Rate limit exceeded');
            return null;
        }

        // Calculate cost based on pricing model
        $cost = $this->calculateCost($advert, 'click');

        // Check budget
        if ($advert->budget && ($advert->spent + $cost) > $advert->budget) {
            $advert->update(['is_paused' => true]);
            return null;
        }

        // Record click
        $click = AdClick::create([
            'advert_id' => $advert->id,
            'impression_id' => $impressionId,
            'publisher_id' => $publisherId,
            'ip_address' => $visitorData['ip_address'],
            'user_agent' => $visitorData['user_agent'],
            'country_code' => $visitorData['country_code'],
            'device_type' => $visitorData['device_type'],
            'browser' => $visitorData['browser'],
            'os' => $visitorData['os'],
            'referrer' => $visitorData['referrer'],
            'destination_url' => $advert->banner_url ?? route('advert.show', $advert->slug),
            'is_valid' => true,
            'cost' => $cost,
            'created_at' => now(),
        ]);

        // Update advert counters and spend
        $advert->increment('clicks');
        $advert->increment('spent', $cost);
        $advert->updateCTR();

        // Update daily stats
        $this->updateDailyStats($advert->id, 'click', $publisherId, $cost);

        return $click;
    }

    /**
     * Get visitor data (IP, country, device, etc.)
     */
    protected function getVisitorData(): array
    {
        $agent = new Agent();
        $ip = request()->ip();

        return [
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'country_code' => $this->getCountryCode($ip),
            'device_type' => $this->getDeviceType($agent),
            'browser' => $agent->browser(),
            'os' => $agent->platform(),
            'referrer' => request()->header('referer'),
        ];
    }

    /**
     * Get device type from Agent
     */
    protected function getDeviceType(Agent $agent): string
    {
        if ($agent->isMobile()) {
            return 'mobile';
        } elseif ($agent->isTablet()) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Get country code from IP (basic implementation)
     */
    protected function getCountryCode(string $ip): ?string
    {
        // For localhost/development
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'NG'; // Default to Nigeria for development
        }

        // In production, use a service like: ip-api.com, ipstack, etc.
        // For now, return null (can be enhanced later)
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
            if ($response) {
                $data = json_decode($response, true);
                return $data['countryCode'] ?? null;
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }

    /**
     * Calculate cost based on pricing model
     */
    protected function calculateCost(Advert $advert, string $type): float
    {
        if ($advert->pricing_model === 'cpc' && $type === 'click') {
            return $advert->cpc_rate ?? 0;
        }

        if ($advert->pricing_model === 'cpm' && $type === 'impression') {
            return ($advert->cpm_rate ?? 0) / 1000;
        }

        return 0; // Flat rate campaigns don't charge per action
    }

    /**
     * Update daily statistics
     */
    protected function updateDailyStats(int $advertId, string $type, ?int $publisherId = null, float $cost = 0): void
    {
        $today = now()->toDateString();

        $stats = AdDailyStat::firstOrCreate(
            ['advert_id' => $advertId, 'date' => $today],
            ['impressions' => 0, 'clicks' => 0, 'conversions' => 0, 'ctr' => 0, 'cost' => 0, 'revenue' => 0]
        );

        if ($type === 'impression') {
            $stats->increment('impressions');
        } elseif ($type === 'click') {
            $stats->increment('clicks');
            $stats->increment('cost', $cost);

            // Calculate publisher revenue (70% share)
            if ($publisherId && $cost > 0) {
                $publisherRevenue = $cost * 0.70;
                $stats->increment('revenue', $publisherRevenue);

                // Track publisher earning
                $this->trackPublisherEarning($publisherId, $advertId, $publisherRevenue, $today);
            }
        }

        // Update CTR
        if ($stats->impressions > 0) {
            $stats->ctr = ($stats->clicks / $stats->impressions) * 100;
            $stats->save();
        }
    }

    /**
     * Track publisher earnings
     */
    protected function trackPublisherEarning(int $publisherId, int $advertId, float $revenue, string $date): void
    {
        // Check if record exists
        $existing = DB::table('publisher_earnings')
            ->where('publisher_id', $publisherId)
            ->where('advert_id', $advertId)
            ->where('date', $date)
            ->first();

        if ($existing) {
            // Update existing record
            DB::table('publisher_earnings')
                ->where('publisher_id', $publisherId)
                ->where('advert_id', $advertId)
                ->where('date', $date)
                ->update([
                    'publisher_revenue' => DB::raw("publisher_revenue + {$revenue}"),
                    'amount' => DB::raw("amount + {$revenue}"),
                    'clicks' => DB::raw("clicks + 1"),
                    'updated_at' => now(),
                ]);
        } else {
            // Create new record
            DB::table('publisher_earnings')->insert([
                'publisher_id' => $publisherId,
                'advert_id' => $advertId,
                'date' => $date,
                'publisher_revenue' => $revenue,
                'amount' => $revenue,
                'clicks' => 1,
                'impressions' => 0,
                'advertiser_spend' => $revenue / 0.70, // Calculate full amount (publisher gets 70%)
                'platform_commission' => ($revenue / 0.70) * 0.30,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get ads matching visitor's targeting
     */
    public function getMatchingAds(?string $zone = null, int $limit = 5): array
    {
        $visitorData = $this->getVisitorData();

        $query = Advert::query()
            ->where('is_active', true)
            ->where('is_paused', false)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('budget')
                  ->orWhereRaw('spent < budget');
            });

        $ads = $query->get()->filter(function ($ad) use ($visitorData) {
            return $ad->matchesTargeting($visitorData);
        });

        return $ads->random(min($limit, $ads->count()))->all();
    }
}
