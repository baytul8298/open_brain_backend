<?php
namespace App\Models\Commerce;

use App\Enums\PaymentStatus;
use App\Models\User;
use App\Models\Learn\Course;
use App\Models\Core\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'commerce.payments';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['student_id','course_id','enrollment_id','subscription_id','coupon_id','gross_amount','discount_amount','net_amount','platform_fee','teacher_amount','currency','status','method_id','gateway','gateway_txn_id','gateway_response','gateway_fee','invoice_number','invoice_url','refunded_at','refund_amount','refund_reason','refund_txn_id','paid_at'];
    protected $casts = [
        'status'           => PaymentStatus::class,
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'refunded_at'      => 'datetime',
    ];

    public function student()       { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function course()        { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function enrollment()    { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
    public function coupon()        { return $this->belongsTo(Coupon::class, 'coupon_id'); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class, 'method_id'); }
}
