<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdvertAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'advert_id',
        'date',
        'impressions',
        'clicks',
        'contact_clicks',
        'unique_visitors',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the advert that owns this analytic record
     */
    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    /**
     * Record an impression for an advert
     */
    public static function recordImpression($advertId)
    {
        $today = now()->toDateString();

        $existing = self::where('advert_id', $advertId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            $existing->increment('impressions');
        } else {
            self::create([
                'advert_id' => $advertId,
                'date' => $today,
                'impressions' => 1,
                'clicks' => 0,
                'contact_clicks' => 0,
                'unique_visitors' => 0,
            ]);
        }
    }

    /**
     * Record a click for an advert
     */
    public static function recordClick($advertId)
    {
        $today = now()->toDateString();

        $existing = self::where('advert_id', $advertId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            $existing->increment('clicks');
        } else {
            self::create([
                'advert_id' => $advertId,
                'date' => $today,
                'impressions' => 0,
                'clicks' => 1,
                'contact_clicks' => 0,
                'unique_visitors' => 0,
            ]);
        }
    }

    /**
     * Record a contact click for an advert
     */
    public static function recordContactClick($advertId)
    {
        $today = now()->toDateString();

        $existing = self::where('advert_id', $advertId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            $existing->increment('contact_clicks');
        } else {
            self::create([
                'advert_id' => $advertId,
                'date' => $today,
                'impressions' => 0,
                'clicks' => 0,
                'contact_clicks' => 1,
                'unique_visitors' => 0,
            ]);
        }
    }

    /**
     * Get aggregated stats for an advert within a date range
     */
    public static function getStats($advertId, $startDate = null, $endDate = null)
    {
        $query = self::where('advert_id', $advertId);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $stats = $query->selectRaw('
            SUM(impressions) as total_impressions,
            SUM(clicks) as total_clicks,
            SUM(contact_clicks) as total_contact_clicks,
            SUM(unique_visitors) as total_unique_visitors
        ')->first();

        $totalImpressions = $stats->total_impressions ?? 0;
        $totalClicks = $stats->total_clicks ?? 0;

        return [
            'total_impressions' => $totalImpressions,
            'total_clicks' => $totalClicks,
            'total_contact_clicks' => $stats->total_contact_clicks ?? 0,
            'total_unique_visitors' => $stats->total_unique_visitors ?? 0,
            'ctr' => $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0,
        ];
    }

    /**
     * Get daily analytics for chart display
     */
    public static function getDailyStats($advertId, $days = 30)
    {
        return self::where('advert_id', $advertId)
            ->where('date', '>=', now()->subDays($days))
            ->orderBy('date')
            ->get();
    }
}
