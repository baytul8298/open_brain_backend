<?php
namespace App\Models\Learn;

use App\Models\User;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'learn.lesson_progress';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['student_id','lesson_id','course_id','enrollment_id','is_completed','watch_seconds','last_position_sec','completed_at','first_opened_at','last_opened_at','notes'];
    protected $casts = ['is_completed' => 'boolean', 'completed_at' => 'datetime', 'first_opened_at' => 'datetime', 'last_opened_at' => 'datetime'];

    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function lesson()     { return $this->belongsTo(Lesson::class, 'lesson_id', 'id'); }
    public function course()     { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
