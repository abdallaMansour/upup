<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDrawing extends Model
{
    protected $fillable = [
        'user_id',
        'user_childhood_stage_id',
        'record_date',
        'record_time',
        'media_document_id',
        'title',
        'other_info',
    ];

    protected $casts = [
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
}
