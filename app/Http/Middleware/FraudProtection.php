<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FraudProtection
{
    /**
     * Fraud detection rules
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userId = auth()->id();

        // Only apply to payment and transaction routes
        if ($this->isPaymentRoute($request)) {
            // Check 1: Multiple failed payment attempts from same IP
            $this->checkMultipleFailedPayments($ip);

            // Check 2: Rapid payment attempts (velocity check)
            $this->checkPaymentVelocity($ip, $userId);

            // Check 3: Suspicious payment patterns
            $this->checkSuspiciousPatterns($request, $ip, $userId);

            // Check 4: Account age fraud check (new accounts making high-value transactions)
            if ($userId) {
                $this->checkNewAccountFraud($userId, $request);
            }

            // Check 5: Geographic anomalies
            $this->checkGeographicAnomalies($request, $userId);
        }

        // Check for click fraud on ad platform
        if ($this->isAdClickRoute($request)) {
            $this->checkClickFraud($request, $ip);
        }

        return $next($request);
    }

    protected function isPaymentRoute(Request $request): bool
    {
        return str_contains($request->path(), 'payment') ||
               str_contains($request->path(), 'transaction') ||
               str_contains($request->path(), 'checkout');
    }

    protected function isAdClickRoute(Request $request): bool
    {
        return str_contains($request->path(), 'api/ad/click') ||
               str_contains($request->path(), 'api/ad/impression');
    }

    protected function checkMultipleFailedPayments(string $ip): void
    {
        $cacheKey = 'fraud:failed_payments:' . $ip;
        $failedAttempts = cache()->get($cacheKey, 0);

        if ($failedAttempts >= 5) {
            Log::alert('Fraud alert: Multiple failed payments from IP', [
                'ip' => $ip,
                'failed_attempts' => $failedAttempts,
            ]);

            abort(403, 'Too many failed payment attempts. Please contact support.');
        }
    }

    protected function checkPaymentVelocity(string $ip, ?int $userId): void
    {
        // Check payment attempts in last 5 minutes
        $cacheKey = 'fraud:payment_velocity:' . ($userId ?: $ip);
        $recentPayments = cache()->get($cacheKey, []);
        $now = now()->timestamp;

        // Filter payments from last 5 minutes
        $recentPayments = array_filter($recentPayments, function ($timestamp) use ($now) {
            return ($now - $timestamp) < 300; // 5 minutes
        });

        if (count($recentPayments) >= 3) {
            Log::alert('Fraud alert: High payment velocity', [
                'ip' => $ip,
                'user_id' => $userId,
                'payments_in_5min' => count($recentPayments),
            ]);

            abort(429, 'Too many payment attempts. Please wait before trying again.');
        }

        // Add current attempt
        $recentPayments[] = $now;
        cache()->put($cacheKey, $recentPayments, 600); // 10 minutes
    }

    protected function checkSuspiciousPatterns(Request $request, string $ip, ?int $userId): void
    {
        // Check for amount manipulation attempts
        if ($request->has('amount')) {
            $amount = $request->input('amount');
            if (is_numeric($amount) && ($amount < 0 || $amount > 1000000)) {
                Log::alert('Fraud alert: Suspicious payment amount', [
                    'ip' => $ip,
                    'user_id' => $userId,
                    'amount' => $amount,
                ]);

                abort(400, 'Invalid payment amount');
            }
        }

        // Check for reference/ID manipulation
        if ($request->has('reference') || $request->has('transaction_id')) {
            $reference = $request->input('reference') ?: $request->input('transaction_id');

            // Check if reference contains suspicious patterns
            if (preg_match('/[<>\'"]|script|exec|eval/i', $reference)) {
                Log::alert('Fraud alert: Suspicious reference pattern', [
                    'ip' => $ip,
                    'user_id' => $userId,
                    'reference' => $reference,
                ]);

                abort(400, 'Invalid reference format');
            }
        }
    }

    protected function checkNewAccountFraud(int $userId, Request $request): void
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return;
        }

        $accountAge = now()->diffInHours($user->created_at);

        // New account (< 24 hours) attempting high-value transaction
        if ($accountAge < 24) {
            $amount = $request->input('amount', 0);

            if ($amount > 10000) { // Over ₦10,000
                Log::warning('Fraud alert: New account high-value transaction', [
                    'user_id' => $userId,
                    'account_age_hours' => $accountAge,
                    'amount' => $amount,
                ]);

                // Don't block, but flag for manual review
                DB::table('fraud_alerts')->insert([
                    'user_id' => $userId,
                    'type' => 'new_account_high_value',
                    'severity' => 'medium',
                    'data' => json_encode([
                        'account_age_hours' => $accountAge,
                        'amount' => $amount,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    protected function checkGeographicAnomalies(Request $request, ?int $userId): void
    {
        if (!$userId) {
            return;
        }

        $ip = $request->ip();
        $cacheKey = 'fraud:last_ip:' . $userId;
        $lastIp = cache()->get($cacheKey);

        if ($lastIp && $lastIp !== $ip) {
            // IP changed - could be VPN/proxy or account compromise
            Log::info('User IP changed', [
                'user_id' => $userId,
                'old_ip' => $lastIp,
                'new_ip' => $ip,
            ]);

            // Check if change happened very recently (< 1 hour)
            $lastChangeKey = 'fraud:ip_change_time:' . $userId;
            $lastChange = cache()->get($lastChangeKey);

            if ($lastChange && (now()->timestamp - $lastChange) < 3600) {
                Log::warning('Fraud alert: Rapid IP changes', [
                    'user_id' => $userId,
                    'ip' => $ip,
                ]);
            }

            cache()->put($lastChangeKey, now()->timestamp, 86400); // 24 hours
        }

        cache()->put($cacheKey, $ip, 86400); // 24 hours
    }

    protected function checkClickFraud(Request $request, string $ip): void
    {
        $advertId = $request->query('id') ?? $request->route('id');

        if (!$advertId) {
            return;
        }

        // Check for rapid clicks from same IP
        $cacheKey = 'fraud:clicks:' . $ip . ':' . $advertId;
        $clicks = cache()->get($cacheKey, 0);

        if ($clicks > 10) { // More than 10 clicks per minute
            Log::warning('Fraud alert: Click fraud detected', [
                'ip' => $ip,
                'advert_id' => $advertId,
                'clicks_per_minute' => $clicks,
            ]);

            // Don't count this click
            abort(429, 'Too many requests');
        }

        cache()->put($cacheKey, $clicks + 1, 60); // 1 minute

        // Check for distributed click fraud (same user clicking multiple ads)
        $userClicksKey = 'fraud:user_clicks:' . $ip;
        $uniqueAds = cache()->get($userClicksKey, []);

        if (!in_array($advertId, $uniqueAds)) {
            $uniqueAds[] = $advertId;
        }

        if (count($uniqueAds) > 20) { // Clicked 20+ different ads in 1 hour
            Log::warning('Fraud alert: Distributed click fraud', [
                'ip' => $ip,
                'unique_ads_clicked' => count($uniqueAds),
            ]);
        }

        cache()->put($userClicksKey, $uniqueAds, 3600); // 1 hour
    }

    /**
     * Record failed payment attempt
     */
    public static function recordFailedPayment(string $ip): void
    {
        $cacheKey = 'fraud:failed_payments:' . $ip;
        $failedAttempts = cache()->get($cacheKey, 0);
        cache()->put($cacheKey, $failedAttempts + 1, 3600); // 1 hour
    }
}
