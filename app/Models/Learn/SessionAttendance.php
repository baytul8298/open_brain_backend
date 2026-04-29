<?php
namespace App\Models\Learn;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    protected $table = 'learn.session_attendance';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['session_id','student_id','joined_at','left_at','duration_min','attended'];
    protected $casts = ['attended' => 'boolean', 'joined_at' => 'datetime', 'left_at' => 'datetime'];

    public function liveSession() { return $this->belongsTo(LiveSession::class, 'session_id', 'id'); }
    public function student()     { return $this->belongsTo(User::class, 'student_id', 'id'); }
}
