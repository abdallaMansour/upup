<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAchievement extends Model
{
    public const TYPES = [
        'honor' => 'تكريم',
        'success' => 'نجاح',
        'championship' => 'بطولة',
        'volunteering' => 'تطوع',
        'appreciation' => 'شهادة تقدير',
        'competition' => 'مسابقة',
    ];

    protected $fillable = [
        'user_id',
        'user_childhood_stage_id',
        'record_date',
        'record_time',
        'type',
        'title',
        'place',
        'academic_year',
        'school',
        'certificate_image_document_id',
    ];

    protected $casts = [
        'record_date' => 'date',
        'record_time' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function childhoodStage(): BelongsTo
    {
        return $this->belongsTo(UserChildhoodStage::class, 'user_childhood_stage_id');
    }

    public function certificateImageDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'certificate_image_document_id');
    }

    public function mediaItems(): HasMany
    {
        return $this->hasMany(UserAchievementMedia::class);
    }

    public function photos(): HasMany
    {
        return $this->mediaItems()->where('media_type', 'photo')->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->mediaItems()->where('media_type', 'video')->orderBy('sort_order');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForStage($query, ?int $stageId)
    {
        if ($stageId === null) {
            return $query->whereNull('user_childhood_stage_id');
        }

        return $query->where('user_childhood_stage_id', $stageId);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getRecordTimeFormattedAttribute(): ?string
    {
        $time = $this->record_time;
        if (! $time) {
            return null;
        }
        if (is_string($time) && strlen($time) >= 5) {
            return substr($time, 0, 5);
        }

        return $time;
    }
}
