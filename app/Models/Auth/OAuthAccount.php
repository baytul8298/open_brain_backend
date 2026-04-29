<?php
namespace App\Models\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class OAuthAccount extends Model {
    protected $table = 'auth.oauth_accounts';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['user_id','provider','provider_user_id','access_token','refresh_token','expires_at'];
    protected $casts = ['expires_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class,'user_id','id'); }
}
