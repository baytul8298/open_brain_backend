<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class NotificationType extends Model {
    protected $table = 'core.notification_type';
    protected $fillable = ['name','description'];
}
