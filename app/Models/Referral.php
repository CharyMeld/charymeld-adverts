<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'source',
        'ip_address',
        'user_agent',
        'clicked_at',
        'registered_at',
        'converted_at',
        'commission_earned',
        'status',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'registered_at' => 'datetime',
        'converted_at' => 'datetime',
        'commission_earned' => 'decimal:2',
    ];

    /**
     * Get the referrer user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user
     */
    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Generate a unique referral code
     */
    public static function generateCode($length = 8)
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Track a referral click
     */
    public static function trackClick($code, $ipAddress = null, $userAgent = null)
    {
        $referral = self::where('referral_code', $code)->first();

        if ($referral && !$referral->clicked_at) {
            $referral->update([
                'clicked_at' => now(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);
        }

        return $referral;
    }

    /**
     * Mark referral as registered
     */
    public function markAsRegistered($userId)
    {
        $this->update([
            'referred_id' => $userId,
            'registered_at' => now(),
            'status' => 'active',
        ]);
    }

    /**
     * Mark referral as converted (purchased)
     */
    public function markAsConverted($commissionAmount = 0)
    {
        $this->update([
            'converted_at' => now(),
            'commission_earned' => $commissionAmount,
            'status' => 'completed',
        ]);
    }

    /**
     * Check if referral is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if referral is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
