<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserVerification extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'nin',
        'nin_document',
        'phone_number',
        'whatsapp_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'id_type',
        'id_number',
        'id_document',
        'proof_of_address_document',
        'bank_name',
        'account_number',
        'account_name',
        'business_name',
        'business_registration_number',
        'business_document',
        'verification_status',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'is_nin_verified',
        'is_id_verified',
        'is_address_verified',
        'is_bank_verified',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'verified_at' => 'datetime',
        'is_nin_verified' => 'boolean',
        'is_id_verified' => 'boolean',
        'is_address_verified' => 'boolean',
        'is_bank_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the verification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified this
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if user is fully verified
     */
    public function isFullyVerified(): bool
    {
        return $this->verification_status === 'verified' &&
               $this->is_nin_verified &&
               !empty($this->nin);
    }

    /**
     * Check if verification is pending
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if verification is incomplete
     */
    public function isIncomplete(): bool
    {
        return $this->verification_status === 'incomplete';
    }

    /**
     * Get verification completion percentage
     */
    public function getCompletionPercentage(): int
    {
        $fields = [
            'full_name',
            'phone_number',
            'address',
            'city',
            'state',
            'nin',
            'nin_document',
            'bank_name',
            'account_number',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->{$field})) {
                $completed++;
            }
        }

        return (int) (($completed / count($fields)) * 100);
    }

    /**
     * Get NIN document URL
     */
    public function getNinDocumentUrl(): ?string
    {
        return $this->nin_document ? Storage::url($this->nin_document) : null;
    }

    /**
     * Get ID document URL
     */
    public function getIdDocumentUrl(): ?string
    {
        return $this->id_document ? Storage::url($this->id_document) : null;
    }

    /**
     * Get proof of address document URL
     */
    public function getProofOfAddressUrl(): ?string
    {
        return $this->proof_of_address_document ? Storage::url($this->proof_of_address_document) : null;
    }

    /**
     * Get business document URL
     */
    public function getBusinessDocumentUrl(): ?string
    {
        return $this->business_document ? Storage::url($this->business_document) : null;
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope for pending verifications
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope for incomplete verifications
     */
    public function scopeIncomplete($query)
    {
        return $query->where('verification_status', 'incomplete');
    }
}
