<?php
namespace App\Models\Learn;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'learn.quizzes';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'lesson_id','course_id','title','instructions','time_limit_min',
        'pass_percentage','attempts_allowed','randomize_questions','show_answers_after',
    ];
    protected $casts = ['randomize_questions' => 'boolean', 'show_answers_after' => 'boolean'];

    public function lesson()    { return $this->belongsTo(Lesson::class, 'lesson_id', 'id'); }
    public function course()    { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function questions() { return $this->hasMany(QuizQuestion::class, 'quiz_id', 'id'); }
    public function attempts()  { return $this->hasMany(QuizAttempt::class, 'quiz_id', 'id'); }
}
