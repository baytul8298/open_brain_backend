<?php
namespace App\Models\Learn;

use Illuminate\Database\Eloquent\Model;

class CourseRequirement extends Model
{
    protected $table = 'learn.course_requirements';
    public $timestamps = false;
    protected $fillable = ['course_id','type','content','sort_order'];

    public function course() { return $this->belongsTo(Course::class, 'course_id', 'id'); }
}
