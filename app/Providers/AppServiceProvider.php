<?php

namespace App\Providers;

use App\Models\Advert;
use App\Models\Blog;
use App\Models\Category;
use App\Models\BlogCategory;
use App\Observers\ContentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register observers for automatic sitemap generation
        Advert::observe(ContentObserver::class);
        Blog::observe(ContentObserver::class);
        Category::observe(ContentObserver::class);
        BlogCategory::observe(ContentObserver::class);
    }
}
