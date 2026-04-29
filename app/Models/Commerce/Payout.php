<?php
namespace App\Models\Commerce;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $table = 'commerce.payouts';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['teacher_id','amount','status','method','account_info','processed_at'];
    protected $casts = ['account_info' => 'array', 'processed_at' => 'datetime'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
}
