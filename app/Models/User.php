<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'phone_verified_at',
        'password',
        'banned_at',
        'education_stage_id',
        'education_grade_id',
        'school_name_ar',
        'school_name_en',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function isFullyVerified(): bool
    {
        return $this->email_verified_at !== null
            && $this->phone !== null
            && $this->phone_verified_at !== null;
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at')
            ->whereNotNull('phone')
            ->whereNotNull('phone_verified_at');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function storageConnections()
    {
        return $this->hasMany(StorageConnection::class);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function getActiveSubscriptionAttribute()
    {
        return $this->subscriptions()->active()->latest('expires_at')->first();
    }

    public function educationStage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }

    public function educationGrade()
    {
        return $this->belongsTo(EducationGrade::class, 'education_grade_id');
    }

    /**
     * Get school name for current locale (ar or en) with fallback.
     */
    public function getSchoolNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        $ar = $this->attributes['school_name_ar'] ?? null;
        $en = $this->attributes['school_name_en'] ?? null;

        if ($locale === 'ar') {
            return $ar ?? $en;
        }

        return $en ?? $ar;
    }
}
