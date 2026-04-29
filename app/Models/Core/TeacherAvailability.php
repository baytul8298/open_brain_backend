<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    protected $table = 'core.teacher_availability';

    public $timestamps = false;

    protected $fillable = ['user_id', 'day_of_week', 'time_slot'];

    protected $casts = [
        'day_of_week' => 'integer',
    ];
}
