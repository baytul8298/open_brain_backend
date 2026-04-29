<?php
namespace App\Models\Core;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class Profile extends Model {
    protected $table = 'core.profiles';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id','first_name','last_name','display_name','avatar_url','cover_url','bio','date_of_birth','gender','country','city','address','contact_no','timezone','language','notifications_prefs'];
    protected $casts = ['notifications_prefs'=>'array','date_of_birth'=>'date'];
    public function user() { return $this->belongsTo(User::class,'id','id'); }
    public function getFullNameAttribute(): string { return trim("{$this->first_name} {$this->last_name}"); }
}
