<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\User;

class MonitorFraudActivity extends Command
{
    protected $signature = 'fraud:monitor';
    protected $description = 'Monitor and detect fraudulent activity';

    public function handle()
    {
        $this->info('Starting fraud monitoring...');

        // 1. Check for suspicious transaction patterns
        $this->checkSuspiciousTransactions();

        // 2. Check for abnormal click patterns
        $this->checkClickFraud();

        // 3. Check for account takeover attempts
        $this->checkAccountTakeover();

        // 4. Review and cleanup old fraud alerts
        $this->cleanupOldAlerts();

        $this->info('Fraud monitoring completed.');
    }

    protected function checkSuspiciousTransactions()
    {
        $this->info('Checking for suspicious transactions...');

        // Find users with multiple failed transactions
        $suspiciousUsers = DB::table('transactions')
            ->select('user_id', DB::raw('COUNT(*) as failed_count'))
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('user_id')
            ->having('failed_count', '>=', 3)
            ->get();

        foreach ($suspiciousUsers as $user) {
            $this->warn("User {$user->user_id} has {$user->failed_count} failed transactions in 24h");

            DB::table('fraud_alerts')->insert([
                'user_id' => $user->user_id,
                'type' => 'multiple_failed_transactions',
                'severity' => 'medium',
                'data' => json_encode(['failed_count' => $user->failed_count]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info("Found {$suspiciousUsers->count()} users with suspicious transaction patterns");
    }

    protected function checkClickFraud()
    {
        $this->info('Checking for click fraud...');

        // Check for ads with abnormally high click-through rates
        $suspiciousAds = DB::table('ad_clicks')
            ->select('advert_id', DB::raw('COUNT(*) as clicks'), DB::raw('COUNT(DISTINCT ip_address) as unique_ips'))
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('advert_id')
            ->get()
            ->filter(function ($ad) {
                // If same ad was clicked more than 50 times from less than 5 IPs, it's suspicious
                return $ad->clicks > 50 && $ad->unique_ips < 5;
            });

        foreach ($suspiciousAds as $ad) {
            $this->warn("Advert {$ad->advert_id} has {$ad->clicks} clicks from only {$ad->unique_ips} IPs");

            $advert = DB::table('adverts')->where('id', $ad->advert_id)->first();
            if ($advert) {
                DB::table('fraud_alerts')->insert([
                    'user_id' => $advert->user_id,
                    'type' => 'click_fraud',
                    'severity' => 'high',
                    'data' => json_encode([
                        'advert_id' => $ad->advert_id,
                        'clicks' => $ad->clicks,
                        'unique_ips' => $ad->unique_ips,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->info("Found {$suspiciousAds->count()} ads with potential click fraud");
    }

    protected function checkAccountTakeover()
    {
        $this->info('Checking for account takeover attempts...');

        // Look for accounts with sudden changes in behavior
        // (e.g., different IP, different payment methods, etc.)

        $recentTransactions = DB::table('transactions')
            ->select('user_id', DB::raw('COUNT(DISTINCT JSON_EXTRACT(metadata, "$.ip_address")) as ip_count'))
            ->where('created_at', '>=', now()->subHours(6))
            ->groupBy('user_id')
            ->having('ip_count', '>', 3)
            ->get();

        foreach ($recentTransactions as $user) {
            $this->warn("User {$user->user_id} used {$user->ip_count} different IPs in 6 hours");

            DB::table('fraud_alerts')->insert([
                'user_id' => $user->user_id,
                'type' => 'account_takeover_suspected',
                'severity' => 'critical',
                'data' => json_encode(['ip_count' => $user->ip_count]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info("Found {$recentTransactions->count()} potential account takeover attempts");
    }

    protected function cleanupOldAlerts()
    {
        $this->info('Cleaning up old fraud alerts...');

        // Mark old pending alerts as reviewed
        $updated = DB::table('fraud_alerts')
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(30))
            ->update([
                'status' => 'resolved',
                'admin_notes' => 'Auto-resolved: No action taken within 30 days',
                'reviewed_at' => now(),
            ]);

        $this->info("Auto-resolved {$updated} old fraud alerts");
    }
}
