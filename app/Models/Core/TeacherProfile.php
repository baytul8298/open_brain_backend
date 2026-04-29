<?php

namespace App\Models\Core;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $table = 'core.teacher_profiles';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'teacher_type_id', 'headline', 'specializations', 'qualifications',
        'experience_years', 'institution', 'department',
        'verified', 'verified_at', 'verified_by', 'id_document_url', 'id_verified',
        'rating_avg', 'rating_count', 'total_students', 'total_courses', 'total_revenue',
        'payout_account', 'payout_threshold', 'commission_rate',
        'is_featured', 'social_links', 'languages_taught',
        'session_types', 'is_flexible_time',
    ];

    protected $casts = [
        'specializations'  => 'array',
        'qualifications'   => 'array',
        'payout_account'   => 'array',
        'social_links'     => 'array',
        'languages_taught' => 'array',
        'session_types'    => 'array',
        'verified'         => 'boolean',
        'id_verified'      => 'boolean',
        'is_featured'      => 'boolean',
        'is_flexible_time' => 'boolean',
        'verified_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function teacherType()
    {
        return $this->belongsTo(TeacherType::class, 'teacher_type_id');
    }

    public function educations()
    {
        return $this->hasMany(TeacherEducation::class, 'user_id', 'user_id')
                    ->orderBy('sort_order')->orderByDesc('start_year');
    }

    public function experience()
    {
        return $this->hasOne(TeacherExperience::class, 'user_id', 'user_id')
                    ->where('is_current', true);
    }

    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'core.teacher_subjects',
            'user_id',
            'subject_id',
            'user_id',
            'id'
        );
    }

    public function gradeLevels()
    {
        return $this->belongsToMany(
            GradeLevel::class,
            'core.teacher_grade_levels',
            'user_id',
            'grade_level_id',
            'user_id',
            'id'
        );
    }

    public function teachingStyles()
    {
        return $this->hasMany(TeacherTeachingStyle::class, 'user_id', 'user_id')
                    ->orderBy('sort_order');
    }

    public function availability()
    {
        return $this->hasMany(TeacherAvailability::class, 'user_id', 'user_id')
                    ->orderBy('day_of_week')->orderBy('time_slot');
    }

    public function verifications()
    {
        return $this->hasMany(TeacherVerification::class, 'user_id', 'user_id')
                    ->orderByDesc('submitted_at');
    }
}
