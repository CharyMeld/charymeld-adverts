<?php

namespace App\Console\Commands;

use App\Mail\WeeklyDigest;
use App\Models\NewsletterSubscriber;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send-digest {--test-email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly digest newsletter to all verified subscribers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting weekly digest newsletter...');

        // Get top adverts from the past week
        $topAdverts = Advert::active()
            ->where('created_at', '>=', now()->subWeek())
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Get trending categories (most adverts this week)
        $trendingCategories = Category::withCount(['adverts' => function ($query) {
            $query->where('created_at', '>=', now()->subWeek());
        }])
            ->having('adverts_count', '>', 0)
            ->orderBy('adverts_count', 'desc')
            ->take(6)
            ->get();

        // Get recent blog posts
        $recentBlogs = Blog::published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Test mode - send to specific email
        if ($testEmail = $this->option('test-email')) {
            $this->info("Test mode: Sending to {$testEmail}");

            $testSubscriber = new NewsletterSubscriber([
                'email' => $testEmail,
                'name' => 'Test User',
            ]);

            try {
                Mail::to($testEmail)->send(new WeeklyDigest(
                    $testSubscriber,
                    $topAdverts,
                    $trendingCategories,
                    $recentBlogs
                ));
                $this->info("Test email sent successfully to {$testEmail}");
            } catch (\Exception $e) {
                $this->error("Failed to send test email: " . $e->getMessage());
                Log::error('Weekly digest test email failed', [
                    'email' => $testEmail,
                    'error' => $e->getMessage()
                ]);
            }

            return Command::SUCCESS;
        }

        // Get all verified and active subscribers
        $subscribers = NewsletterSubscriber::active()
            ->verified()
            ->get();

        if ($subscribers->isEmpty()) {
            $this->warn('No verified subscribers found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$subscribers->count()} verified subscribers");

        $sentCount = 0;
        $failedCount = 0;

        $bar = $this->output->createProgressBar($subscribers->count());
        $bar->start();

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new WeeklyDigest(
                    $subscriber,
                    $topAdverts,
                    $trendingCategories,
                    $recentBlogs
                ));

                $sentCount++;

            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Weekly digest email failed', [
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'error' => $e->getMessage()
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Log results
        Log::info('Weekly digest newsletter sent', [
            'total_subscribers' => $subscribers->count(),
            'sent' => $sentCount,
            'failed' => $failedCount,
            'top_adverts' => $topAdverts->count(),
            'trending_categories' => $trendingCategories->count(),
            'recent_blogs' => $recentBlogs->count(),
        ]);

        $this->info("Weekly digest completed!");
        $this->info("Successfully sent: {$sentCount}");

        if ($failedCount > 0) {
            $this->warn("Failed: {$failedCount}");
        }

        return Command::SUCCESS;
    }
}
