<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use SoftDeletes;

    protected $table = 'core.class_roomes';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'course_id',
        'teacher_id',
        'subject_id',
        'name',
        'slug',
        'description',
        'class_type',
        'status',
        'max_students',
        'enrolled_count',
        'start_date',
        'end_date',
        'grade_level_ids',
        'language_id',
        'meeting_platform',
        'meeting_url',
        'meeting_id',
        'meeting_password',
        'thumbnail_url',
        'is_featured',
        'is_public',
        'notes',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'is_featured'   => 'boolean',
        'is_public'     => 'boolean',
        'start_date'    => 'date',
        'end_date'      => 'date',
        'published_at'  => 'datetime',
        'deleted_at'    => 'datetime',
    ];
}
