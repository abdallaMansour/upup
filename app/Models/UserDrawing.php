<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDrawing extends Model
{
    use HasTranslatableFields;

    protected $fillable = [
        'user_id',
        'user_childhood_stage_id',
        'show_in_education',
        'record_date',
        'record_time',
        'media_document_id',
        'title_ar',
        'title_en',
        'other_info_ar',
        'other_info_en',
    ];

    protected $casts = [
        'show_in_education' => 'boolean',
        'record_date' => 'date',
        'record_time' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mediaDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'media_document_id');
    }

    public function childhoodStage(): BelongsTo
    {
        return $this->belongsTo(UserChildhoodStage::class, 'user_childhood_stage_id');
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

    public function getTitleAttribute(): string
    {
        return $this->getTranslated('title');
    }

    public function getOtherInfoAttribute(): string
    {
        return $this->getTranslated('other_info');
    }
}
