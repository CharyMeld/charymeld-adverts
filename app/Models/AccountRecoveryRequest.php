<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountRecoveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'description',
        'verification_document',
        'alternative_email',
        'phone',
        'status',
        'user_id',
        'handled_by',
        'admin_notes',
        'resolved_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInvestigating($query)
    {
        return $query->where('status', 'investigating');
    }
}
