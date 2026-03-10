<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHeightWeight extends Model
{
    protected $table = 'user_height_weights';

    protected $fillable = [
        'user_id',
        'user_childhood_stage_id',
        'record_date',
        'record_time',
        'height',
        'weight',
        'image_document_id',
        'video_document_id',
    ];

    protected $casts = [
        'record_date' => 'date',
        'record_time' => 'string',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function childhoodStage(): BelongsTo
    {
        return $this->belongsTo(UserChildhoodStage::class, 'user_childhood_stage_id');
    }

    public function imageDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'image_document_id');
    }

    public function videoDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'video_document_id');
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
