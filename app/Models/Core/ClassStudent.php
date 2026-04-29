<?php
namespace App\Models\Core;

use App\Enums\ClassStudentStatus;
use App\Models\User;
use App\Models\Commerce\Enrollment;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = 'core.class_students';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'class_id', 'student_id', 'enrollment_id', 'status', 'joined_at', 'left_at', 'notes',
    ];

    protected $casts = [
        'status'    => ClassStudentStatus::class,
        'joined_at' => 'datetime',
        'left_at'   => 'datetime',
    ];

    public function classRoom()  { return $this->belongsTo(ClassRoom::class, 'class_id', 'id'); }
    public function student()    { return $this->belongsTo(User::class, 'student_id', 'id'); }
    public function enrollment() { return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id'); }
}
