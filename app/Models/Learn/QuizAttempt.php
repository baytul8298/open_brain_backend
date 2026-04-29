<?php
namespace App\Models\Learn;

use App\Models\User;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'learn.quiz_attempts';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['student_id','quiz_id','enrollment_id','attempt_number','started_at','submitted_at','time_taken_sec','score','max_score','percentage','passed','answers'];
    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime', 'passed' => 'boolean', 'answers' => 'array'];

    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function quiz()       { return $this->belongsTo(Quiz::class, 'quiz_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
