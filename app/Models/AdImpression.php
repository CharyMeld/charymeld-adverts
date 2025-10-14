<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdImpression extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'advert_id',
        'publisher_id',
        'ip_address',
        'user_agent',
        'country_code',
        'device_type',
        'browser',
        'os',
        'referrer',
        'page_url',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function click()
    {
        return $this->hasOne(AdClick::class, 'impression_id');
    }
}
