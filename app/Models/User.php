<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'user_type',
        'address',
        'avatar',
        'is_active',
        'google_id',
        'facebook_id',
        'twitter_id',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is advertiser
     */
    public function isAdvertiser(): bool
    {
        return $this->role === 'advertiser';
    }

    /**
     * Check if user is publisher
     */
    public function isPublisher(): bool
    {
        return in_array($this->user_type ?? '', ['publisher', 'both']);
    }

    /**
     * Relationships
     */
    public function adverts()
    {
        return $this->hasMany(Advert::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function verification()
    {
        return $this->hasOne(UserVerification::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function sentDirectMessages()
    {
        return $this->hasMany(DirectMessage::class, 'sender_id');
    }

    public function receivedDirectMessages()
    {
        return $this->hasMany(DirectMessage::class, 'receiver_id');
    }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    /**
     * Check if user is following another user
     */
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    /**
     * Follow a user
     */
    public function follow($userId)
    {
        if (!$this->isFollowing($userId)) {
            $this->following()->attach($userId);
        }
    }

    /**
     * Unfollow a user
     */
    public function unfollow($userId)
    {
        $this->following()->detach($userId);
    }

    /**
     * Check if user has submitted verification
     */
    public function hasVerification(): bool
    {
        return $this->verification()->exists();
    }

    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        return $this->hasVerification() && $this->verification->verification_status === 'verified';
    }

    /**
     * Check if verification is pending
     */
    public function hasVerificationPending(): bool
    {
        return $this->hasVerification() && $this->verification->verification_status === 'pending';
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'user_type' => $this->user_type,
        ];
    }
}
