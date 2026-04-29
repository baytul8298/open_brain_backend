<?php
namespace App\Models\Core;

use App\Enums\ClassStatus;
use App\Enums\ClassType;
use App\Models\User;
use App\Models\Learn\Course;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'core.classes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'course_id', 'teacher_id', 'subject_id', 'name', 'slug', 'description',
        'class_type', 'status', 'max_students', 'enrolled_count', 'start_date', 'end_date',
        'grade_level_ids', 'language_id', 'meeting_platform', 'meeting_url', 'meeting_id',
        'meeting_password', 'thumbnail_url', 'is_featured', 'is_public', 'notes',
        'created_by', 'published_at', 'deleted_at',
    ];

    protected $casts = [
        'class_type'      => ClassType::class,
        'status'          => ClassStatus::class,
        'grade_level_ids' => 'array',
        'is_featured'     => 'boolean',
        'is_public'       => 'boolean',
        'start_date'      => 'date',
        'end_date'        => 'date',
        'published_at'    => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    public function teacher()       { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function createdBy()     { return $this->belongsTo(User::class, 'created_by', 'id'); }
    public function course()        { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function subject()       { return $this->belongsTo(Subject::class, 'subject_id'); }
    public function language()      { return $this->belongsTo(LanguageMedium::class, 'language_id'); }
    public function schedules()     { return $this->hasMany(ClassSchedule::class, 'class_id', 'id'); }
    public function classStudents() { return $this->hasMany(ClassStudent::class, 'class_id', 'id'); }
    public function students()      { return $this->hasManyThrough(User::class, ClassStudent::class, 'class_id', 'id', 'id', 'student_id'); }
}
