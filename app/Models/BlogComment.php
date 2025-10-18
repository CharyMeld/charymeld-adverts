<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'status',
    ];

    /**
     * Relationships
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('status', 'approved');
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeParentComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Accessors
     */
    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }
}
