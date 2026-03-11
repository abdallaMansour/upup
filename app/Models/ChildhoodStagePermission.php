<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class ChildhoodStagePermission extends Model
{
    protected $fillable = [
        'user_childhood_stage_id',
        'grantee_name',
        'grantee_email',
        'pin_hash',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function childhoodStage(): BelongsTo
    {
        return $this->belongsTo(UserChildhoodStage::class, 'user_childhood_stage_id');
    }

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function verifyPin(string $pin): bool
    {
        return Hash::check($pin, $this->pin_hash);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
