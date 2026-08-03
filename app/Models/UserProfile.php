<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'website',
        'location',
        'birth_date',
        'cover_image',
        'social_links',
        'occupation',
        'company',
        'privacy',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'social_links' => 'array',
    ];

    /**
     * Get the user that owns the profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
