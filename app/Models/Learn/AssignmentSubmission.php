<?php
namespace App\Models\Learn;

use App\Enums\SubmissionStatus;
use App\Models\User;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $table = 'learn.assignment_submissions';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['assignment_id','student_id','enrollment_id','status','submitted_at','is_late','files','student_note','marks','grade','feedback','graded_by','graded_at','returned_at','resubmit_allowed','resubmit_count'];
    protected $casts = [
        'status'           => SubmissionStatus::class,
        'submitted_at'     => 'datetime',
        'is_late'          => 'boolean',
        'files'            => 'array',
        'graded_at'        => 'datetime',
        'returned_at'      => 'datetime',
        'resubmit_allowed' => 'boolean',
    ];

    public function assignment() { return $this->belongsTo(Assignment::class, 'assignment_id', 'id'); }
    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
