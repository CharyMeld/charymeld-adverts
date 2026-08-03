<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Domain
    |--------------------------------------------------------------------------
    */
    'domain' => env('APP_DOMAIN', 'charymeld-adverts.teamodigitalsolutions.com'),

    /*
    |--------------------------------------------------------------------------
    | Force HTTPS
    |--------------------------------------------------------------------------
    | When enabled, the ForceHttps middleware will redirect all HTTP requests
    | to HTTPS. Set FORCE_HTTPS=true in your .env for production.
    */
    'force_https' => env('FORCE_HTTPS', env('APP_FORCE_HTTPS', false)),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    */
    'available_locales' => [
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
        ],
        'fr' => [
            'name' => 'French',
            'native' => 'Français',
            'flag' => '🇫🇷',
        ],
        'es' => [
            'name' => 'Spanish',
            'native' => 'Español',
            'flag' => '🇪🇸',
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'flag' => '🇸🇦',
            'rtl' => true,
        ],
        'pt' => [
            'name' => 'Portuguese',
            'native' => 'Português',
            'flag' => '🇵🇹',
        ],
    ],
];
