<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TeacherTeachingStyle extends Model
{
    protected $table = 'core.teacher_teaching_styles';

    protected $fillable = [
        'user_id', 'name', 'description', 'color', 'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
