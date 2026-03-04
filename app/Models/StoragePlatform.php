<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoragePlatform extends Model
{
    protected $fillable = [
        'provider',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function storageConnections(): HasMany
    {
        return $this->hasMany(StorageConnection::class, 'provider', 'provider');
    }
}
