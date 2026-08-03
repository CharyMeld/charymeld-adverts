<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublisherZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'publisher_id',
        'zone_name',
        'zone_code',
        'ad_type',
        'size',
        'description',
        'is_active',
        'impressions',
        'clicks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($zone) {
            if (empty($zone->zone_code)) {
                $zone->zone_code = strtoupper(Str::random(8));
            }
        });
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function earnings()
    {
        return $this->hasMany(PublisherEarning::class, 'zone_id');
    }

    public function getCTR()
    {
        return $this->impressions > 0 ? ($this->clicks / $this->impressions) * 100 : 0;
    }
}
