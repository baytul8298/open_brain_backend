<?php
namespace App\Models\Learn;

use App\Enums\AssignmentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'learn.assignments';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['course_id','teacher_id','section_id','title','description','instructions','attachments','status','due_date','total_marks','pass_marks','allow_late_submit','late_penalty_pct','max_file_size_mb','allowed_file_types'];
    protected $casts = [
        'status'            => AssignmentStatus::class,
        'due_date'          => 'datetime',
        'attachments'       => 'array',
        'allowed_file_types'=> 'array',
        'allow_late_submit' => 'boolean',
    ];

    public function course()      { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function teacher()     { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function section()     { return $this->belongsTo(Section::class, 'section_id', 'id'); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class, 'assignment_id', 'id'); }
}
