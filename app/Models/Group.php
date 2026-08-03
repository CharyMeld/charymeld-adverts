<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'created_by',
        'privacy',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
        });
    }

    /**
     * Get the user who created the group
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all members of the group
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all group messages
     */
    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    /**
     * Check if user is a member
     */
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user is admin or moderator
     */
    public function isAdminOrModerator($userId)
    {
        return $this->members()
            ->where('user_id', $userId)
            ->whereIn('group_members.role', ['admin', 'moderator'])
            ->exists();
    }
}
