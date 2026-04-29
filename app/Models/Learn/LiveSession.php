<?php
namespace App\Models\Learn;

use App\Enums\SessionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    protected $table = 'learn.live_sessions';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'course_id','lesson_id','teacher_id','title','description','status',
        'scheduled_start_at','scheduled_end_at','actual_start_at','actual_end_at',
        'platform','meeting_url','meeting_id','recording_url','recording_available','attendee_count','notes',
    ];
    protected $casts = [
        'status'              => SessionStatus::class,
        'scheduled_start_at'  => 'datetime',
        'scheduled_end_at'    => 'datetime',
        'actual_start_at'     => 'datetime',
        'actual_end_at'       => 'datetime',
        'recording_available' => 'boolean',
    ];

    public function course()      { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function lesson()      { return $this->belongsTo(Lesson::class, 'lesson_id', 'id'); }
    public function teacher()     { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function attendances() { return $this->hasMany(SessionAttendance::class, 'session_id', 'id'); }
}
