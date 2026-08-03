<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reactable_id',
        'reactable_type',
        'type',
    ];

    // Available reaction types
    const TYPES = [
        'like' => '👍',
        'love' => '❤️',
        'laugh' => '😂',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😠',
    ];

    /**
     * Get the user who reacted
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reactable model (Video, GroupMessage, etc.)
     */
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get emoji for reaction type
     */
    public function getEmojiAttribute(): string
    {
        return self::TYPES[$this->type] ?? '👍';
    }
}
