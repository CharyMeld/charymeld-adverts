<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'message',
        'attachment',
    ];

    /**
     * Get the group
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user who sent the message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reactions for this message
     */
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
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
}
