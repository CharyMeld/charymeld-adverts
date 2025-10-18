<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ContentObserver
{
    /**
     * Regenerate sitemap when content is created
     */
    public function created($model)
    {
        $this->regenerateSitemap();
    }

    /**
     * Regenerate sitemap when content is updated
     */
    public function updated($model)
    {
        $this->regenerateSitemap();
    }

    /**
     * Regenerate sitemap when content is deleted
     */
    public function deleted($model)
    {
        $this->regenerateSitemap();
    }

    /**
     * Regenerate the sitemap asynchronously
     */
    protected function regenerateSitemap()
    {
        try {
            // Run sitemap generation in queue to avoid blocking
            dispatch(function () {
                Artisan::call('sitemap:generate');
                Log::info('Sitemap regenerated automatically');
            })->afterResponse();
        } catch (\Exception $e) {
            Log::error('Failed to regenerate sitemap: ' . $e->getMessage());
        }
    }
}
