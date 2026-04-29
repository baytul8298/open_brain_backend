<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TeacherExperience extends Model
{
    protected $table = 'core.teacher_experiences';

    protected $fillable = [
        'user_id', 'role_type', 'start_month', 'start_year',
        'description', 'expertise_tags', 'is_current',
    ];

    protected $casts = [
        'expertise_tags' => 'array',
        'is_current'     => 'boolean',
        'start_month'    => 'integer',
        'start_year'     => 'integer',
    ];
}
