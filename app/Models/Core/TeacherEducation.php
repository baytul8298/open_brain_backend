<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TeacherEducation extends Model
{
    protected $table = 'core.teacher_educations';

    protected $fillable = [
        'user_id', 'type', 'title', 'institution',
        'start_year', 'end_year', 'is_current', 'sort_order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'start_year' => 'integer',
        'end_year'   => 'integer',
        'sort_order' => 'integer',
    ];
}
