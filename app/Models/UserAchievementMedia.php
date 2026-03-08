<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievementMedia extends Model
{
    protected $fillable = [
        'user_achievement_id',
        'user_document_id',
        'media_type',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(UserAchievement::class, 'user_achievement_id');
    }

    public function userDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class);
    }
}
