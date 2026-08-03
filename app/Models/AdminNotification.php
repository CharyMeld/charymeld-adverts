<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create a support request notification
     */
    public static function createSupportRequest($conversationId, $userId, $userName)
    {
        return static::create([
            'type' => 'support_request',
            'title' => 'New Support Request',
            'message' => "$userName has requested human support",
            'data' => [
                'conversation_id' => $conversationId,
                'user_id' => $userId,
                'user_name' => $userName,
            ],
            'action_url' => route('admin.support-chat.show', $conversationId),
        ]);
    }

    /**
     * Create a payout request notification
     */
    public static function createPayoutRequest($payoutId, $publisherId, $amount)
    {
        return static::create([
            'type' => 'payout_request',
            'title' => 'New Payout Request',
            'message' => "Publisher has requested payout of ₦" . number_format($amount, 2),
            'data' => [
                'payout_id' => $payoutId,
                'publisher_id' => $publisherId,
                'amount' => $amount,
            ],
            'action_url' => route('admin.payouts.index'),
        ]);
    }
}
