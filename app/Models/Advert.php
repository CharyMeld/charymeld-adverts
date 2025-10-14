<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Advert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'status',
        'plan',
        'ad_type',
        'budget',
        'daily_budget',
        'spent',
        'pricing_model',
        'cpc_rate',
        'cpm_rate',
        'location',
        'phone',
        'email',
        'is_active',
        'is_paused',
        'views',
        'impressions',
        'clicks',
        'ctr',
        'target_countries',
        'target_devices',
        'target_keywords',
        'target_age_min',
        'target_age_max',
        'target_gender',
        'banner_image',
        'banner_size',
        'banner_url',
        'published_at',
        'expires_at',
        'campaign_start',
        'campaign_end',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_paused' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'campaign_start' => 'datetime',
        'campaign_end' => 'datetime',
        'price' => 'decimal:2',
        'budget' => 'decimal:2',
        'daily_budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'cpc_rate' => 'decimal:2',
        'cpm_rate' => 'decimal:2',
        'ctr' => 'decimal:2',
        'target_countries' => 'array',
        'target_devices' => 'array',
        'target_keywords' => 'array',
        'target_gender' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($advert) {
            if (empty($advert->slug)) {
                $advert->slug = Str::slug($advert->title) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(AdvertImage::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function impressions()
    {
        return $this->hasMany(AdImpression::class, 'advert_id');
    }

    public function clicks()
    {
        return $this->hasMany(AdClick::class, 'advert_id');
    }

    public function conversions()
    {
        return $this->hasMany(AdConversion::class, 'advert_id');
    }

    public function dailyStats()
    {
        return $this->hasMany(AdDailyStat::class, 'advert_id');
    }

    /**
     * Get the primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(AdvertImage::class)->where('is_primary', true);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('plan', 'featured');
    }

    public function scopePremium($query)
    {
        return $query->where('plan', 'premium');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Check if advert is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if campaign budget is exhausted
     */
    public function isBudgetExhausted(): bool
    {
        return $this->budget && $this->spent >= $this->budget;
    }

    /**
     * Check if campaign is running
     */
    public function isRunning(): bool
    {
        return $this->is_active
            && !$this->is_paused
            && $this->status === 'approved'
            && !$this->isExpired()
            && !$this->isBudgetExhausted();
    }

    /**
     * Calculate and update CTR
     */
    public function updateCTR(): void
    {
        if ($this->impressions > 0) {
            $this->ctr = ($this->clicks / $this->impressions) * 100;
            $this->save();
        }
    }

    /**
     * Check if ad matches targeting criteria
     */
    public function matchesTargeting(array $visitorData): bool
    {
        // Country targeting
        if (!empty($this->target_countries)) {
            if (!in_array($visitorData['country_code'] ?? '', $this->target_countries)) {
                return false;
            }
        }

        // Device targeting
        if (!empty($this->target_devices)) {
            if (!in_array($visitorData['device_type'] ?? '', $this->target_devices)) {
                return false;
            }
        }

        // Keywords targeting (match against page content or category)
        if (!empty($this->target_keywords)) {
            $pageContent = strtolower($visitorData['page_content'] ?? '');
            $matched = false;
            foreach ($this->target_keywords as $keyword) {
                if (str_contains($pageContent, strtolower($keyword))) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                return false;
            }
        }

        return true;
    }
}
