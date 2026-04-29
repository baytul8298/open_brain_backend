<?php
namespace App\Models\Learn;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $table = 'learn.course_schedule';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['course_id','day_of_week','start_time','end_time','timezone','is_active','effective_from','effective_until'];
    protected $casts = ['is_active' => 'boolean', 'effective_from' => 'date', 'effective_until' => 'date'];

    public function course() { return $this->belongsTo(Course::class, 'course_id', 'id'); }
}
