<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Meilisearch\Client;

class MeilisearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('scout.meilisearch');

            return new Client(
                $config['host'],
                $config['key']
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
