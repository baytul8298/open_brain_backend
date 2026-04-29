<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class CourseFormat extends Model {
    protected $table = 'core.course_format';
    protected $fillable = ['name','description'];
}
