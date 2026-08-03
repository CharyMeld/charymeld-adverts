<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SearchQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'results_count',
        'user_id',
        'session_id',
        'ip_address',
    ];

    /**
     * Get popular searches
     */
    public static function getPopularSearches($limit = 10)
    {
        return self::select('query', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get trending searches (growing in popularity)
     */
    public static function getTrendingSearches($limit = 10)
    {
        $lastWeek = self::select('query', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'query');

        $previousWeek = self::select('query', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'query');

        $trending = [];
        foreach ($lastWeek as $query => $count) {
            $prevCount = $previousWeek->get($query, 0);
            $growth = $prevCount > 0 ? (($count - $prevCount) / $prevCount) * 100 : 100;
            $trending[] = [
                'query' => $query,
                'count' => $count,
                'growth' => round($growth, 1),
            ];
        }

        usort($trending, fn($a, $b) => $b['growth'] <=> $a['growth']);

        return collect(array_slice($trending, 0, $limit));
    }
}
