<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class TeacherType extends Model {
    protected $table = 'core.teacher_type';
    protected $fillable = ['name','description'];
}
