<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationGrade extends Model
{
    protected $fillable = ['education_stage_id', 'name_ar', 'name_en'];

    public function stage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }

    public function getNameAttribute(): string
    {
        if (app()->getLocale() === 'en') {
            $nameEn = $this->attributes['name_en'] ?? null;
            if ($nameEn !== null && $nameEn !== '') {
                return $nameEn;
            }
        }

        return $this->attributes['name_ar'] ?? '';
    }
}
