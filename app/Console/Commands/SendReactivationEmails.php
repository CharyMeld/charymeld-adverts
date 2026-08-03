<?php

namespace App\Console\Commands;

use App\Mail\UserReactivation;
use App\Models\User;
use App\Models\Advert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReactivationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reactivate {--days=30} {--test-email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reactivation emails to inactive users (default: inactive for 30+ days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inactiveDays = $this->option('days');
        $this->info("Finding users inactive for {$inactiveDays}+ days...");

        // Test mode - send to specific email
        if ($testEmail = $this->option('test-email')) {
            $testUser = User::where('email', $testEmail)->first();

            if (!$testUser) {
                $this->error("User with email {$testEmail} not found");
                return Command::FAILURE;
            }

            $this->info("Test mode: Sending to {$testEmail}");
            $this->sendReactivationEmail($testUser, true);

            return Command::SUCCESS;
        }

        // Find inactive users
        $inactiveUsers = User::where('last_login_at', '<=', now()->subDays($inactiveDays))
            ->orWhereNull('last_login_at')
            ->whereHas('adverts') // Only users who have posted ads before
            ->get();

        if ($inactiveUsers->isEmpty()) {
            $this->warn("No inactive users found (inactive for {$inactiveDays}+ days)");
            return Command::SUCCESS;
        }

        $this->info("Found {$inactiveUsers->count()} inactive users");

        $sentCount = 0;
        $failedCount = 0;

        $bar = $this->output->createProgressBar($inactiveUsers->count());
        $bar->start();

        foreach ($inactiveUsers as $user) {
            if ($this->sendReactivationEmail($user)) {
                $sentCount++;
            } else {
                $failedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Log results
        Log::info('User reactivation emails sent', [
            'inactive_days' => $inactiveDays,
            'total_users' => $inactiveUsers->count(),
            'sent' => $sentCount,
            'failed' => $failedCount,
        ]);

        $this->info("Reactivation emails completed!");
        $this->info("Successfully sent: {$sentCount}");

        if ($failedCount > 0) {
            $this->warn("Failed: {$failedCount}");
        }

        return Command::SUCCESS;
    }

    /**
     * Send reactivation email to a user
     */
    private function sendReactivationEmail(User $user, bool $isTest = false): bool
    {
        try {
            // Get personalized adverts based on user's previous activity
            $userCategories = $user->adverts()
                ->pluck('category_id')
                ->unique()
                ->toArray();

            $personalizedAdverts = [];
            if (!empty($userCategories)) {
                $personalizedAdverts = Advert::active()
                    ->whereIn('category_id', $userCategories)
                    ->where('created_at', '>=', now()->subWeek())
                    ->orderBy('views', 'desc')
                    ->take(3)
                    ->get();
            }

            // If no personalized ads, get top ads
            if ($personalizedAdverts->isEmpty()) {
                $personalizedAdverts = Advert::active()
                    ->where('created_at', '>=', now()->subWeek())
                    ->orderBy('views', 'desc')
                    ->take(3)
                    ->get();
            }

            // Special offers (featured ad discount, etc.)
            $specialOffers = [
                [
                    'title' => '20% OFF Featured Listings',
                    'description' => 'Use code WELCOME20 to get discount on your next featured ad',
                    'code' => 'WELCOME20',
                    'valid_until' => now()->addDays(7)->format('F d, Y'),
                ],
            ];

            Mail::to($user->email)->send(new UserReactivation(
                $user,
                $personalizedAdverts,
                $specialOffers
            ));

            if ($isTest) {
                $this->info("Test email sent successfully to {$user->email}");
            }

            return true;

        } catch (\Exception $e) {
            Log::error('User reactivation email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            if ($isTest) {
                $this->error("Failed to send test email: " . $e->getMessage());
            }

            return false;
        }
    }
}
