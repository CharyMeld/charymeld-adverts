<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule fraud monitoring to run every hour
Schedule::command('fraud:monitor')->hourly();

// Schedule sitemap generation to run daily at 2 AM
Schedule::command('sitemap:generate')->dailyAt('02:00');

// Schedule weekly newsletter digest to run every Monday at 9 AM
Schedule::command('newsletter:send-digest')->weeklyOn(1, '09:00');

// Schedule user reactivation emails to run weekly on Wednesdays at 10 AM
Schedule::command('users:reactivate')->weeklyOn(3, '10:00');
