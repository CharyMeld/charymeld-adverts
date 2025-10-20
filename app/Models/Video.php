<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'video_path',
        'thumbnail_path',
        'duration',
        'file_size',
        'mime_type',
        'views',
        'privacy',
    ];

    protected $casts = [
        'views' => 'integer',
        'duration' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the user who uploaded the video
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reactions for this video
     */
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    /**
     * Get comments for this video
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Check if user has reacted
     */
    public function hasReaction($userId, $type = null)
    {
        $query = $this->reactions()->where('user_id', $userId);
        if ($type) {
            $query->where('type', $type);
        }
        return $query->exists();
    }

    /**
     * Get reaction counts grouped by type
     */
    public function getReactionCounts()
    {
        return $this->reactions()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
