<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your analytics and tracking services here.
    |
    */

    'enabled' => env('ANALYTICS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4
    |--------------------------------------------------------------------------
    |
    | Your Google Analytics 4 Measurement ID (format: G-XXXXXXXXXX)
    | Get it from: https://analytics.google.com/
    |
    */
    'google_analytics' => [
        'enabled' => !empty(env('GOOGLE_ANALYTICS_ID')),
        'measurement_id' => env('GOOGLE_ANALYTICS_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta Pixel (Facebook Pixel)
    |--------------------------------------------------------------------------
    |
    | Your Meta Pixel ID for Facebook & Instagram tracking
    | Get it from: https://business.facebook.com/events_manager
    |
    */
    'meta_pixel' => [
        'enabled' => !empty(env('META_PIXEL_ID')),
        'pixel_id' => env('META_PIXEL_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter (X) Pixel
    |--------------------------------------------------------------------------
    |
    | Your Twitter Pixel ID for conversion tracking
    | Get it from: https://ads.twitter.com/
    |
    */
    'twitter_pixel' => [
        'enabled' => !empty(env('TWITTER_PIXEL_ID')),
        'pixel_id' => env('TWITTER_PIXEL_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Hotjar (Heatmaps & Session Recording)
    |--------------------------------------------------------------------------
    |
    | Your Hotjar Site ID for heatmaps and user behavior tracking
    | Get it from: https://www.hotjar.com/
    |
    */
    'hotjar' => [
        'enabled' => !empty(env('HOTJAR_ID')),
        'site_id' => env('HOTJAR_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Tracking
    |--------------------------------------------------------------------------
    |
    | Configure which events should be tracked automatically
    |
    */
    'track_events' => [
        'ad_view' => true,
        'ad_click' => true,
        'ad_contact' => true,
        'ad_share' => true,
        'user_register' => true,
        'user_login' => true,
        'ad_create' => true,
        'campaign_create' => true,
        'payment' => true,
        'newsletter_subscribe' => true,
        'blog_view' => true,
        'search' => true,
    ],
];
