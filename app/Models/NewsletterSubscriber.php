<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'verification_token',
        'verified_at',
        'is_active',
        'preferences',
        'source',
        'ip_address',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->verification_token)) {
                $subscriber->verification_token = Str::random(64);
            }
        });
    }

    /**
     * Check if subscriber is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Verify the subscriber
     */
    public function verify()
    {
        $this->update([
            'verified_at' => now(),
            'verification_token' => null,
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified subscribers
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for unverified subscribers
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }
}
