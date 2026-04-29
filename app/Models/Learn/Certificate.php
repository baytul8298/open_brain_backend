<?php
namespace App\Models\Learn;

use App\Enums\CertificateStatus;
use App\Models\User;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'learn.certificates';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['student_id','course_id','enrollment_id','status','certificate_number','issued_at','pdf_url','final_score'];
    protected $casts = ['status' => CertificateStatus::class, 'issued_at' => 'datetime'];

    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function course()     { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
