<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageConnection extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'name',
        'is_active',
        'credentials',
        'root_folder_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
    ];

    public const PROVIDERS = [
        'google_drive' => 'Google Drive',
        'wasabi' => 'Wasabi',
        'dropbox' => 'Dropbox',
        'one_drive' => 'OneDrive',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(UserDocument::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return self::PROVIDERS[$this->provider] ?? $this->provider;
    }
}
