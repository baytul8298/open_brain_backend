<?php
namespace App\Models\Core;
use App\Enums\MediaType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class Media extends Model {
    protected $table = 'core.media';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['uploader_id','media_type','original_name','storage_key','public_url','cdn_url','mime_type','size_bytes','width_px','height_px','duration_sec','metadata','is_public'];
    protected $casts = ['metadata'=>'array','is_public'=>'boolean','media_type'=>MediaType::class];
    public function uploader() { return $this->belongsTo(User::class,'uploader_id','id'); }
}
