<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'advert_id',
        'image_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
