<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'advert_id',
        'impression_id',
        'publisher_id',
        'ip_address',
        'user_agent',
        'country_code',
        'device_type',
        'browser',
        'os',
        'referrer',
        'destination_url',
        'is_valid',
        'cost',
        'created_at',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'cost' => 'decimal:4',
        'created_at' => 'datetime',
    ];

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    public function impression()
    {
        return $this->belongsTo(AdImpression::class, 'impression_id');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function conversion()
    {
        return $this->hasOne(AdConversion::class, 'click_id');
    }
}
