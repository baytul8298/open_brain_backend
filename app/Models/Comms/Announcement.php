<?php
namespace App\Models\Comms;

use App\Models\User;
use App\Models\Learn\Course;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'comms.announcements';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['teacher_id','course_id','title','body','is_pinned','send_email','send_push','published_at'];
    protected $casts = ['is_pinned' => 'boolean', 'send_email' => 'boolean', 'send_push' => 'boolean', 'published_at' => 'datetime'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function course()  { return $this->belongsTo(Course::class, 'course_id', 'id'); }
}
