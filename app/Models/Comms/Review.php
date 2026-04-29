<?php
namespace App\Models\Comms;

use App\Models\User;
use App\Models\Learn\Course;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'comms.reviews';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['course_id','teacher_id','student_id','enrollment_id','rating','title','body','is_verified','is_featured','is_hidden','helpful_count','reply','reply_at'];
    protected $casts = ['is_verified' => 'boolean', 'is_featured' => 'boolean', 'is_hidden' => 'boolean', 'reply_at' => 'datetime'];

    public function course()     { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function teacher()    { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
