<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\MeilisearchServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'advertiser' => \App\Http\Middleware\IsAdvertiser::class,
            'publisher' => \App\Http\Middleware\IsPublisher::class,
            'bot.detect' => \App\Http\Middleware\DetectBots::class,
            'fraud.protect' => \App\Http\Middleware\FraudProtection::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
        ]);

        // Apply global web middleware
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Apply HTTPS middleware globally in production
        if (env('APP_FORCE_HTTPS', false)) {
            $middleware->web(append: [
                \App\Http\Middleware\ForceHttps::class,
            ]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
