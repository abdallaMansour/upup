<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserChildhoodStage extends Model
{
    protected $fillable = [
        'user_id',
        'footprint_document_id',
        'name',
        'mother_name',
        'father_name',
        'naming_reason',
        'birth_date',
        'birth_time',
        'gender',
        'height',
        'weight',
        'blood_type',
        'doctor',
        'birth_place',
        'first_photo_document_id',
        'first_video_document_id',
        'first_gift_document_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_time' => 'string',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function footprintDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'footprint_document_id');
    }

    public function firstPhotoDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'first_photo_document_id');
    }

    public function firstVideoDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'first_video_document_id');
    }

    public function firstGiftDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'first_gift_document_id');
    }

    public function mediaItems(): HasMany
    {
        return $this->hasMany(UserChildhoodMedia::class);
    }

    public function otherPhotos(): HasMany
    {
        return $this->mediaItems()->where('media_type', 'other_photo')->orderBy('sort_order');
    }

    public function otherVideos(): HasMany
    {
        return $this->mediaItems()->where('media_type', 'other_video')->orderBy('sort_order');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getBirthTimeFormattedAttribute(): ?string
    {
        $time = $this->birth_time;
        if (! $time) {
            return null;
        }
        if (is_string($time) && strlen($time) >= 5) {
            return substr($time, 0, 5);
        }

        return $time;
    }
}
