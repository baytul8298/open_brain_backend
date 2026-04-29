<?php
namespace App\Models\Commerce;

use App\Enums\EnrollmentStatus;
use App\Models\User;
use App\Models\Learn\Course;
use App\Models\Learn\Lesson;
use App\Models\Learn\Certificate;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'commerce.enrollments';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['student_id','course_id','status','enrolled_at','expires_at','cancelled_at','cancellation_reason','progress_pct','last_lesson_id','last_accessed_at','completed_at','certificate_id','current_plan_price'];
    protected $casts = [
        'status'           => EnrollmentStatus::class,
        'enrolled_at'      => 'datetime',
        'expires_at'       => 'datetime',
        'cancelled_at'     => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at'     => 'datetime',
    ];

    public function student()     { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function course()      { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function lastLesson()  { return $this->belongsTo(Lesson::class, 'last_lesson_id', 'id'); }
    public function certificate() { return $this->belongsTo(Certificate::class, 'certificate_id', 'id'); }
}
