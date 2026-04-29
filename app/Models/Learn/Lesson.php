<?php
namespace App\Models\Learn;

use App\Models\Core\LessonType;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'learn.lessons';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'section_id','course_id','title','description','lesson_type_id','sort_order',
        'is_free_preview','is_published','video_url','video_duration_sec','document_url',
        'document_pages','external_url','content_json','scheduled_at','live_platform',
        'live_meeting_url','live_meeting_id','live_password','thumbnail_url','attachments',
    ];
    protected $casts = [
        'is_free_preview' => 'boolean',
        'is_published'    => 'boolean',
        'content_json'    => 'array',
        'attachments'     => 'array',
        'scheduled_at'    => 'datetime',
    ];

    public function section()    { return $this->belongsTo(Section::class, 'section_id', 'id'); }
    public function course()     { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function lessonType() { return $this->belongsTo(LessonType::class, 'lesson_type_id'); }
    public function quizzes()    { return $this->hasMany(Quiz::class, 'lesson_id', 'id'); }
}
