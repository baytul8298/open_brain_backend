<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class LessonType extends Model {
    protected $table = 'core.lesson_type';
    protected $fillable = ['name','description'];
}
