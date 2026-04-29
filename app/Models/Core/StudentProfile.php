<?php
namespace App\Models\Core;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class StudentProfile extends Model {
    protected $table = 'core.student_profiles';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['user_id','grade_level_id','school_name','board','roll_number','registration_number','parent_name','parent_phone','parent_email','interested_subjects','points','last_activity_at'];
    protected $casts = ['interested_subjects'=>'array','last_activity_at'=>'datetime','updated_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class,'user_id','id'); }
    public function gradeLevel() { return $this->belongsTo(GradeLevel::class,'grade_level_id'); }
}
