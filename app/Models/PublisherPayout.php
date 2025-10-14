<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublisherPayout extends Model
{
    protected $fillable = [
        'publisher_id',
        'amount',
        'status',
        'payment_method',
        'payment_details',
        'notes',
        'payment_proof',
        'admin_notes',
        'requested_at',
        'processed_at',
        'paid_at',
        'cancelled_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the publisher that owns the payout
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    /**
     * Get earnings associated with this payout
     */
    public function earnings()
    {
        return $this->hasMany(PublisherEarning::class, 'payout_id');
    }
}
