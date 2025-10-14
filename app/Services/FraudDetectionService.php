<?php

namespace App\Services;

use App\Models\IpBlacklist;
use App\Models\BotPattern;
use App\Models\FraudLog;
use App\Models\IpRateLimit;
use Illuminate\Support\Facades\Cache;

class FraudDetectionService
{
    // Rate limits
    const MAX_CLICKS_PER_IP_PER_AD_PER_DAY = 5;
    const MAX_CLICKS_PER_IP_PER_DAY = 50;
    const MIN_SECONDS_BETWEEN_CLICKS = 2;

    /**
     * Check if IP is blacklisted
     */
    public function isBlacklisted(string $ip): bool
    {
        return Cache::remember("blacklist:{$ip}", 3600, function () use ($ip) {
            return IpBlacklist::where('ip_address', $ip)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->exists();
        });
    }

    /**
     * Check if user agent is a bot
     */
    public function isBot(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $userAgent = strtolower($userAgent);

        // Get bot patterns from cache
        $patterns = Cache::remember('bot_patterns', 3600, function () {
            return BotPattern::where('is_active', true)->pluck('pattern')->toArray();
        });

        foreach ($patterns as $pattern) {
            if (str_contains($userAgent, strtolower($pattern))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check rate limit for IP
     */
    public function checkRateLimit(string $ip, int $advertId): bool
    {
        $cacheKey = "rate_limit:{$ip}:{$advertId}";

        // Check clicks per IP per ad per day
        $clicksForAd = Cache::remember($cacheKey, 3600, function () use ($ip, $advertId) {
            return IpRateLimit::where('ip_address', $ip)
                ->where('advert_id', $advertId)
                ->where('action_type', 'click')
                ->where('window_start', '>=', now()->startOfDay())
                ->sum('count');
        });

        if ($clicksForAd >= self::MAX_CLICKS_PER_IP_PER_AD_PER_DAY) {
            return false;
        }

        // Check total clicks per IP per day
        $totalClicks = Cache::remember("rate_limit:{$ip}:total", 3600, function () use ($ip) {
            return IpRateLimit::where('ip_address', $ip)
                ->where('action_type', 'click')
                ->where('window_start', '>=', now()->startOfDay())
                ->sum('count');
        });

        if ($totalClicks >= self::MAX_CLICKS_PER_IP_PER_DAY) {
            $this->blacklistIp($ip, 'Rate limit exceeded', now()->addHours(24));
            return false;
        }

        // Check rapid clicks (time between clicks)
        $lastClick = Cache::get("last_click:{$ip}:{$advertId}");
        if ($lastClick && (time() - $lastClick) < self::MIN_SECONDS_BETWEEN_CLICKS) {
            $this->logFraud($advertId, null, 'rapid_clicks', 'Clicks less than 2 seconds apart');
            return false;
        }

        // Record this click in rate limit
        $this->recordRateLimit($ip, $advertId);

        // Remember last click time
        Cache::put("last_click:{$ip}:{$advertId}", time(), 60);

        // Clear cache for next check
        Cache::forget($cacheKey);

        return true;
    }

    /**
     * Record rate limit entry
     */
    protected function recordRateLimit(string $ip, int $advertId): void
    {
        IpRateLimit::create([
            'ip_address' => $ip,
            'advert_id' => $advertId,
            'action_type' => 'click',
            'count' => 1,
            'window_start' => now(),
            'window_end' => now()->endOfDay(),
            'is_blocked' => false,
        ]);
    }

    /**
     * Blacklist an IP address
     */
    public function blacklistIp(string $ip, string $reason = 'fraud', $expiresAt = null): void
    {
        IpBlacklist::create([
            'ip_address' => $ip,
            'reason' => $reason,
            'description' => "Auto-blocked: {$reason}",
            'blocked_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        Cache::forget("blacklist:{$ip}");
    }

    /**
     * Log fraudulent activity
     */
    public function logFraud(int $advertId, ?int $clickId, string $fraudType, string $details, int $severity = 5): void
    {
        FraudLog::create([
            'advert_id' => $advertId,
            'click_id' => $clickId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fraud_type' => $fraudType,
            'severity' => $severity,
            'details' => $details,
            'metadata' => [
                'referrer' => request()->header('referer'),
                'url' => request()->fullUrl(),
                'time' => now()->toIso8601String(),
            ],
        ]);

        // Auto-blacklist if high severity or repeated offenses
        if ($severity >= 8) {
            $this->blacklistIp(request()->ip(), $fraudType);
        }
    }

    /**
     * Detect VPN/Proxy (basic check)
     */
    public function isVpnOrProxy(string $ip): bool
    {
        // Check common VPN/proxy headers
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',
        ];

        foreach ($headers as $header) {
            if (request()->header($header)) {
                return true;
            }
        }

        // You can integrate with VPN detection APIs here
        // e.g., proxycheck.io, iphub.info, etc.

        return false;
    }

    /**
     * Get fraud score for an IP (0-100, higher = more suspicious)
     */
    public function getFraudScore(string $ip): int
    {
        $score = 0;

        // Check if blacklisted
        if ($this->isBlacklisted($ip)) {
            $score += 100;
            return min($score, 100);
        }

        // Check fraud logs
        $recentFraud = FraudLog::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $score += min($recentFraud * 10, 30);

        // Check rate limit violations
        $rateLimitViolations = IpRateLimit::where('ip_address', $ip)
            ->where('is_blocked', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $score += min($rateLimitViolations * 15, 40);

        // Check if VPN
        if ($this->isVpnOrProxy($ip)) {
            $score += 20;
        }

        return min($score, 100);
    }
}
