<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'guest_email',
        'title',
        'ai_personality',
        'context',
        'last_message_at',
        'support_status',
        'support_user_id',
        'support_requested_at',
        'support_connected_at',
    ];

    protected $casts = [
        'context' => 'array',
        'last_message_at' => 'datetime',
        'support_requested_at' => 'datetime',
        'support_connected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')
            ->where('sender_type', 'ai')
            ->where('is_read', false);
    }

    public function supportAgent()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    public function isWaitingForSupport()
    {
        return $this->support_status === 'requested';
    }

    public function hasActiveSupport()
    {
        return $this->support_status === 'connected';
    }
}
