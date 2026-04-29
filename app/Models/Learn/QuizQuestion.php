<?php
namespace App\Models\Learn;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'learn.quiz_questions';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['quiz_id','question_text','question_image','question_type','options','correct_answer','explanation','points','sort_order'];
    protected $casts = ['options' => 'array'];

    public function quiz() { return $this->belongsTo(Quiz::class, 'quiz_id', 'id'); }
}
