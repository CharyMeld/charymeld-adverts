<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublisherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'website_url',
        'website_name',
        'website_description',
        'website_category',
        'monthly_visitors',
        'status',
        'rejection_reason',
        'revenue_share',
        'payment_method',
        'payment_details',
        'minimum_payout',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'revenue_share' => 'decimal:2',
        'minimum_payout' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zones()
    {
        return $this->hasMany(PublisherZone::class, 'publisher_id', 'user_id');
    }

    public function earnings()
    {
        return $this->hasMany(PublisherEarning::class, 'publisher_id', 'user_id');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
}
