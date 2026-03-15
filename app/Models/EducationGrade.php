<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationGrade extends Model
{
    protected $fillable = ['education_stage_id', 'name'];

    public function stage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }
}
