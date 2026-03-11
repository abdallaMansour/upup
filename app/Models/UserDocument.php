<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDocument extends Model
{
    protected $fillable = [
        'user_id',
        'storage_connection_id',
        'parent_id',
        'name',
        'original_name',
        'path',
        'external_id',
        'mime_type',
        'size',
        'provider',
        'type',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storageConnection(): BelongsTo
    {
        return $this->belongsTo(StorageConnection::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserDocument::class, 'parent_id');
    }

    public function isFolder(): bool
    {
        return $this->type === 'folder';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['بايت', 'ك.ب', 'م.ب', 'ج.ب'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getViewUrlAttribute(): ?string
    {
        if (! $this->external_id) {
            return null;
        }
        if ($this->provider === 'google_drive') {
            if ($this->isFolder()) {
                return 'https://drive.google.com/drive/folders/' . $this->external_id;
            }

            return 'https://drive.google.com/file/d/' . $this->external_id . '/view';
        }
        if ($this->provider === 'wasabi') {
            if ($this->isFolder()) {
                return null;
            }

            return route('dashboard.documents.view-file', $this);
        }

        return null;
    }

    /**
     * URL suitable for embedding in img src - streams file through our server to avoid CORS/external display issues.
     * Returns null for non-image files or folders.
     * For Wasabi, mime_type may be null (e.g. from sync) - we infer from file extension.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->external_id || $this->isFolder()) {
            return null;
        }
        $mime = $this->mime_type ?? '';
        if (str_starts_with($mime, 'image/')) {
            return route('dashboard.documents.embed', $this);
        }
        // Wasabi sync may not set mime_type - infer from extension for common image formats
        if ($this->provider === 'wasabi' && $this->looksLikeImageByExtension()) {
            return route('dashboard.documents.embed', $this);
        }

        return null;
    }

    /**
     * Check if file appears to be an image based on extension (when mime_type is unknown).
     */
    public function looksLikeImageByExtension(): bool
    {
        $name = $this->original_name ?? $this->name ?? $this->path ?? '';
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'heic'];

        return in_array($ext, $imageExtensions, true);
    }

    public function getFileIconAttribute(): string
    {
        if ($this->isFolder()) {
            return 'bx-folder';
        }
        $mime = $this->mime_type ?? '';
        if (str_starts_with($mime, 'image/')) {
            return 'bx-image';
        }
        if (str_starts_with($mime, 'video/')) {
            return 'bx-video';
        }
        if (str_starts_with($mime, 'audio/')) {
            return 'bx-music';
        }
        if (in_array($mime, ['application/pdf'])) {
            return 'bx-file-blank';
        }
        return 'bx-file';
    }
}
