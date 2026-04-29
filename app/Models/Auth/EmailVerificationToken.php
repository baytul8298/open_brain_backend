<?php
namespace App\Models\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class EmailVerificationToken extends Model {
    protected $table = 'auth.email_verification_tokens';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['user_id','token_hash','used','expires_at'];
    protected $casts = ['used'=>'boolean','expires_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class,'user_id','id'); }
}
