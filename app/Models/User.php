<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
}
