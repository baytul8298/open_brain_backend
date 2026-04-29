<?php
namespace App\Models\Commerce;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TeacherWallet extends Model
{
    protected $table = 'commerce.teacher_wallet';
    protected $primaryKey = 'teacher_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['teacher_id','available','pending','lifetime_earned'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
}
