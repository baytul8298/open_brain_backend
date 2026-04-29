<?php
namespace App\Models\Commerce;

use App\Models\User;
use App\Models\Learn\Course;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'commerce.coupons';
    public $timestamps = false;
    protected $fillable = ['teacher_id','course_id','code','description','discount_type','discount_value','max_uses','used_count','min_order_amount','valid_from','valid_until','is_active'];
    protected $casts = ['valid_from' => 'datetime', 'valid_until' => 'datetime', 'is_active' => 'boolean'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function course()  { return $this->belongsTo(Course::class, 'course_id', 'id'); }
}
