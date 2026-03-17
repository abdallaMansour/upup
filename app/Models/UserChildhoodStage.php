<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserChildhoodStage extends Model
{
    use HasTranslatableFields;

    protected $fillable = [
        'user_id',
        'is_public',
        'education_linked_sections',
        'footprint_document_id',
        'name_ar',
        'name_en',
        'theme',
        'default_language',
        'mother_name_ar',
        'mother_name_en',
        'father_name_ar',
        'father_name_en',
        'naming_reason_ar',
        'naming_reason_en',
        'birth_date',
        'birth_time',
        'gender',
        'height',
        'weight',
        'blood_type',
        'birth_place_ar',
        'birth_place_en',
        'doctor_ar',
        'doctor_en',
        'first_photo_document_id',
        'first_video_document_id',
        'first_gift_document_id',
        'cover_image_document_id',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'education_linked_sections' => 'array',
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

    public function coverImageDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class, 'cover_image_document_id');
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

    public function heightWeights(): HasMany
    {
        return $this->hasMany(UserHeightWeight::class, 'user_childhood_stage_id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'user_childhood_stage_id');
    }

    public function voices(): HasMany
    {
        return $this->hasMany(UserVoice::class, 'user_childhood_stage_id');
    }

    public function drawings(): HasMany
    {
        return $this->hasMany(UserDrawing::class, 'user_childhood_stage_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(UserVisit::class, 'user_childhood_stage_id');
    }

    public function injuries(): HasMany
    {
        return $this->hasMany(UserInjury::class, 'user_childhood_stage_id');
    }

    public function otherEvents(): HasMany
    {
        return $this->hasMany(UserOtherEvent::class, 'user_childhood_stage_id');
    }

    public function temporaryPermissions(): HasMany
    {
        return $this->hasMany(ChildhoodStagePermission::class, 'user_childhood_stage_id');
    }

    public function isSectionLinkedToEducation(string $section): bool
    {
        $sections = $this->education_linked_sections ?? [];

        return in_array($section, $sections, true);
    }

    /**
     * Check if a document belongs to this stage (cover, first photo, footprint, media, etc.).
     */
    public function documentBelongsToStage(?UserDocument $document): bool
    {
        if (! $document) {
            return false;
        }

        $directIds = array_filter([
            $this->cover_image_document_id,
            $this->first_photo_document_id,
            $this->footprint_document_id,
            $this->first_video_document_id,
            $this->first_gift_document_id,
        ]);

        if (in_array($document->id, $directIds, true)) {
            return true;
        }

        if ($this->mediaItems()->where('user_document_id', $document->id)->exists()) {
            return true;
        }

        if ($this->heightWeights()->where('image_document_id', $document->id)->exists()) {
            return true;
        }

        if ($this->visits()->where('media_document_id', $document->id)->exists()) {
            return true;
        }

        if ($this->otherEvents()->where('media_document_id', $document->id)->exists()) {
            return true;
        }

        if ($this->achievements()->where('certificate_image_document_id', $document->id)->exists()) {
            return true;
        }

        return $this->achievements()->whereHas('mediaItems', fn ($q) => $q->where('user_document_id', $document->id))->exists();
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

    public function getNameAttribute(): string
    {
        return $this->getTranslated('name');
    }

    public function getMotherNameAttribute(): string
    {
        return $this->getTranslated('mother_name');
    }

    public function getFatherNameAttribute(): string
    {
        return $this->getTranslated('father_name');
    }

    public function getNamingReasonAttribute(): string
    {
        return $this->getTranslated('naming_reason');
    }

    public function getBirthPlaceAttribute(): string
    {
        return $this->getTranslated('birth_place');
    }

    public function getDoctorAttribute(): string
    {
        return $this->getTranslated('doctor');
    }

    public function getAgeInYearsAttribute(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    /**
     * Returns life stage: 'child', 'teenager', or 'adult' based on configurable age limits from site settings.
     * Defaults to 'child' when birth_date is missing.
     */
    public function getLifeStageAttribute(): string
    {
        $age = $this->age_in_years;
        if ($age === null) {
            return 'child';
        }

        $settings = SiteSetting::get();
        $childhoodMax = (int) ($settings->age_stage_childhood_max ?? 11);
        $teenagerMax = (int) ($settings->age_stage_teenager_max ?? 17);

        if ($age <= $childhoodMax) {
            return 'child';
        }
        if ($age <= $teenagerMax) {
            return 'teenager';
        }

        return 'adult';
    }
}
