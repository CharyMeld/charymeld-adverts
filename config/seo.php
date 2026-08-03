<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Search Engine Verification
    |--------------------------------------------------------------------------
    |
    | Add your verification codes from Google Search Console and Bing Webmaster Tools
    | These meta tags are automatically added to all pages
    |
    */

    'google_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    'bing_verification' => env('BING_SITE_VERIFICATION', ''),

    /*
    |--------------------------------------------------------------------------
    | Default SEO Settings
    |--------------------------------------------------------------------------
    |
    | Default values for SEO meta tags across the site
    |
    */

    'defaults' => [
        'title' => env('APP_NAME', 'CharyMeld Adverts') . ' - Your Trusted Advertising Platform',
        'description' => 'CharyMeld Adverts is the leading advertising platform connecting advertisers with publishers. Post ads, reach your audience, and grow your business.',
        'keywords' => 'advertising, classifieds, marketplace, ads, publishers, advertisers, Nigeria, Africa',
        'author' => 'CharyMeld Adverts',
        'og_image' => '/images/og-image.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Settings
    |--------------------------------------------------------------------------
    |
    | Configure social media metadata for better sharing
    |
    */

    'social' => [
        'twitter_handle' => env('TWITTER_HANDLE', '@charymeld'),
        'facebook_app_id' => env('FACEBOOK_APP_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Indexing Settings
    |--------------------------------------------------------------------------
    |
    | Control which pages should be indexed by search engines
    |
    */

    'index' => [
        'allow_indexing' => env('SEO_ALLOW_INDEXING', true),
        'noindex_routes' => [
            'login',
            'register',
            'password.*',
            'verification.*',
            'dashboard.*',
            'admin.*',
            'payment.*',
        ],
    ],

];
