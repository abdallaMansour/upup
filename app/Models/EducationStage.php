<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationStage extends Model
{
    protected $fillable = ['name_ar', 'name_en'];

    public function grades()
    {
        return $this->hasMany(EducationGrade::class);
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
