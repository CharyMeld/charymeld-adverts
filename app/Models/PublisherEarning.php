<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublisherEarning extends Model
{
    protected $fillable = [
        'publisher_id',
        'advert_id',
        'zone_id',
        'date',
        'impressions',
        'clicks',
        'advertiser_spend',
        'publisher_revenue',
        'amount',
        'platform_commission',
        'status',
        'payout_id',
    ];

    protected $casts = [
        'date' => 'date',
        'advertiser_spend' => 'decimal:2',
        'publisher_revenue' => 'decimal:2',
        'amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
    ];

    /**
     * Get the publisher that owns the earning
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    /**
     * Get the advert associated with this earning
     */
    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class);
    }

    /**
     * Get the zone associated with this earning
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(PublisherZone::class, 'zone_id');
    }

    /**
     * Get the payout this earning belongs to
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(PublisherPayout::class);
    }
}
