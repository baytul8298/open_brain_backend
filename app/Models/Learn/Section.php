<?php
namespace App\Models\Learn;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'learn.sections';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['course_id','title','description','sort_order','is_free'];
    protected $casts = ['is_free' => 'boolean'];

    public function course()  { return $this->belongsTo(Course::class, 'course_id', 'id'); }
    public function lessons() { return $this->hasMany(Lesson::class, 'section_id', 'id'); }
}
