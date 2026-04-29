<?php
namespace App\Models\Comms;

use App\Models\User;
use App\Models\Core\NotificationType;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'comms.notifications';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['user_id','type_id','title','body','data','action_url','is_read','read_at','sent_at','email_sent','push_sent','sms_sent'];
    protected $casts = [
        'data'       => 'array',
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
        'sent_at'    => 'datetime',
        'email_sent' => 'boolean',
        'push_sent'  => 'boolean',
        'sms_sent'   => 'boolean',
    ];

    public function user()             { return $this->belongsTo(User::class, 'user_id', 'id'); }
    public function notificationType() { return $this->belongsTo(NotificationType::class, 'type_id'); }
}
