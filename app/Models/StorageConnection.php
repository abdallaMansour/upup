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
        'is_primary',
        'credentials',
        'root_folder_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
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

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public static function getPrimaryForUser(int $userId): ?self
    {
        return static::where('user_id', $userId)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();
    }

    public static function setAsPrimary(self $connection): void
    {
        static::where('user_id', $connection->user_id)->update(['is_primary' => false]);
        $connection->update(['is_primary' => true]);
    }
}
