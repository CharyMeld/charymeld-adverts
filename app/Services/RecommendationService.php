<?php

namespace App\Services;

use App\Models\Advert;
use App\Models\UserAdView;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    /**
     * Get similar ads based on category, location, and price range
     */
    public function getSimilarAds(Advert $advert, $limit = 6)
    {
        $cacheKey = "similar_ads_{$advert->id}_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($advert, $limit) {
            return Advert::where('id', '!=', $advert->id)
                ->where('category_id', $advert->category_id)
                ->active()
                ->approved()
                ->notExpired()
                ->when($advert->location, function ($query) use ($advert) {
                    $query->where('location', 'like', "%{$advert->location}%");
                })
                ->when($advert->price, function ($query) use ($advert) {
                    // Price range: -30% to +30%
                    $minPrice = $advert->price * 0.7;
                    $maxPrice = $advert->price * 1.3;
                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                })
                ->with(['category', 'primaryImage'])
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get personalized recommendations based on user viewing history
     */
    public function getPersonalizedRecommendations($userId = null, $sessionId = null, $limit = 10)
    {
        if (!$userId && !$sessionId) {
            return $this->getPopularAds($limit);
        }

        // Get user's viewing history
        $viewHistory = UserAdView::when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(!$userId && $sessionId, function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            })
            ->with('category')
            ->latest()
            ->limit(50)
            ->get();

        if ($viewHistory->isEmpty()) {
            return $this->getPopularAds($limit);
        }

        // Get most viewed categories
        $categoryIds = $viewHistory->pluck('category_id')->filter()->toArray();
        $topCategories = array_count_values($categoryIds);
        arsort($topCategories);
        $topCategoryIds = array_slice(array_keys($topCategories), 0, 3);

        // Get ads user hasn't seen
        $viewedAdIds = $viewHistory->pluck('advert_id')->toArray();

        return Advert::whereIn('category_id', $topCategoryIds)
            ->whereNotIn('id', $viewedAdIds)
            ->active()
            ->approved()
            ->notExpired()
            ->with(['category', 'primaryImage'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular/trending ads
     */
    public function getPopularAds($limit = 10)
    {
        $cacheKey = "popular_ads_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($limit) {
            return Advert::active()
                ->approved()
                ->notExpired()
                ->with(['category', 'primaryImage'])
                ->orderByDesc('views')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get recommended ads for homepage based on user preferences
     */
    public function getHomepageRecommendations($userId = null, $sessionId = null, $limit = 20)
    {
        $personalizedAds = $this->getPersonalizedRecommendations($userId, $sessionId, $limit);

        // If we don't have enough personalized ads, fill with popular ads
        if ($personalizedAds->count() < $limit) {
            $needed = $limit - $personalizedAds->count();
            $popularAds = $this->getPopularAds($needed);

            // Exclude ads already in personalized
            $personalizedIds = $personalizedAds->pluck('id')->toArray();
            $popularAds = $popularAds->whereNotIn('id', $personalizedIds);

            $personalizedAds = $personalizedAds->merge($popularAds);
        }

        return $personalizedAds->take($limit);
    }

    /**
     * Get related blog posts based on ad category
     */
    public function getRelatedBlogPosts(Advert $advert, $limit = 3)
    {
        $cacheKey = "related_blogs_{$advert->id}_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($advert, $limit) {
            // Find blog posts with similar keywords or category
            return DB::table('blogs')
                ->where('status', 'published')
                ->where(function ($query) use ($advert) {
                    $keywords = explode(' ', $advert->title);
                    foreach (array_slice($keywords, 0, 5) as $keyword) {
                        if (strlen($keyword) > 3) {
                            $query->orWhere('title', 'like', "%{$keyword}%")
                                  ->orWhere('excerpt', 'like', "%{$keyword}%");
                        }
                    }
                })
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear cache for specific ad
     */
    public function clearAdCache($advertId)
    {
        Cache::forget("similar_ads_{$advertId}_6");
        Cache::forget("related_blogs_{$advertId}_3");
    }

    /**
     * Clear all recommendation caches
     */
    public function clearAllCaches()
    {
        Cache::forget('popular_ads_10');
        // Add more cache clearing as needed
    }
}
