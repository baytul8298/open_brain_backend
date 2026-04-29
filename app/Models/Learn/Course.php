<?php
namespace App\Models\Learn;

use App\Enums\CourseStatus;
use App\Enums\PricingModel;
use App\Models\User;
use App\Models\Core\Subject;
use App\Models\Core\LanguageMedium;
use App\Models\Core\CourseFormat;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'learn.courses';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teacher_id','subject_id','title','slug','short_description','full_description',
        'thumbnail_url','trailer_url','grade_level_ids','board','language_id','format_id',
        'status','tags','pricing_model','price','original_price','currency','max_students',
        'start_date','end_date','session_duration_min','enrolled_count','completed_count',
        'rating_avg','rating_count','total_lessons','total_sections','total_duration_min',
        'published_at','deleted_at',
    ];

    protected $casts = [
        'grade_level_ids' => 'array',
        'tags'            => 'array',
        'status'          => CourseStatus::class,
        'pricing_model'   => PricingModel::class,
        'published_at'    => 'datetime',
        'deleted_at'      => 'datetime',
        'start_date'      => 'date',
        'end_date'        => 'date',
    ];

    public function teacher()     { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function subject()     { return $this->belongsTo(Subject::class, 'subject_id'); }
    public function language()    { return $this->belongsTo(LanguageMedium::class, 'language_id'); }
    public function format()      { return $this->belongsTo(CourseFormat::class, 'format_id'); }
    public function sections()    { return $this->hasMany(Section::class, 'course_id', 'id'); }
    public function lessons()     { return $this->hasMany(Lesson::class, 'course_id', 'id'); }
    public function assignments() { return $this->hasMany(Assignment::class, 'course_id', 'id'); }
    public function liveSessions(){ return $this->hasMany(LiveSession::class, 'course_id', 'id'); }
    public function enrollments() { return $this->hasMany(Enrollment::class, 'course_id', 'id'); }
}
