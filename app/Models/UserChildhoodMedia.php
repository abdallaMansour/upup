<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChildhoodMedia extends Model
{
    protected $fillable = [
        'user_childhood_stage_id',
        'user_document_id',
        'media_type',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function childhoodStage(): BelongsTo
    {
        return $this->belongsTo(UserChildhoodStage::class, 'user_childhood_stage_id');
    }

    public function userDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class);
    }
}
