<?php
namespace App\Models\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class AuthSession extends Model {
    protected $table = 'auth.sessions';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    protected $fillable = ['user_id','refresh_token','device_info','ip_address','is_revoked','expires_at'];
    protected $casts = ['device_info'=>'array','is_revoked'=>'boolean','expires_at'=>'datetime','created_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class,'user_id','id'); }
}
