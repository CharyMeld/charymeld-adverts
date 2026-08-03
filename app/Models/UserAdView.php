<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'advert_id',
        'category_id',
        'ip_address',
    ];

    /**
     * Get the user who viewed the ad
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the advert that was viewed
     */
    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    /**
     * Get the category of the viewed ad
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Record a user viewing an ad
     */
    public static function recordView($advertId, $userId = null, $sessionId = null, $categoryId = null)
    {
        self::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'advert_id' => $advertId,
            'category_id' => $categoryId,
            'ip_address' => request()->ip(),
        ]);
    }
}
