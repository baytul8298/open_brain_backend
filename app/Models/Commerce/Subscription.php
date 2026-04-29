<?php
namespace App\Models\Commerce;

use App\Enums\EnrollmentStatus;
use App\Models\User;
use App\Models\Learn\Course;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'commerce.subscriptions';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['enrollment_id','student_id','course_id','status','billing_cycle','price_per_cycle','currency','current_period_start','current_period_end','next_billing_date','trial_end_at','cancelled_at','pause_until','auto_renew'];
    protected $casts = [
        'status'               => EnrollmentStatus::class,
        'current_period_start' => 'datetime',
        'current_period_end'   => 'datetime',
        'next_billing_date'    => 'datetime',
        'trial_end_at'         => 'datetime',
        'cancelled_at'         => 'datetime',
        'pause_until'          => 'datetime',
        'auto_renew'           => 'boolean',
    ];

    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function course()     { return $this->belongsTo(Course::class, 'course_id', 'id'); }
}
