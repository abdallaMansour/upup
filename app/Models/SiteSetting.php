<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'privacy_policy',
        'terms_and_conditions',
        'age_stage_childhood_max',
        'age_stage_teenager_max',
        'age_stage_adult_max',
    ];

    protected $casts = [
        'age_stage_childhood_max' => 'integer',
        'age_stage_teenager_max' => 'integer',
        'age_stage_adult_max' => 'integer',
    ];

    /**
     * Get the singleton site settings instance.
     */
    public static function get(): self
    {
        $settings = static::first();
        if (! $settings) {
            $settings = static::create([
                'privacy_policy' => '',
                'terms_and_conditions' => '',
                'age_stage_childhood_max' => 11,
                'age_stage_teenager_max' => 17,
                'age_stage_adult_max' => 120,
            ]);
        }
        return $settings;
    }
}
