<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class PaymentMethod extends Model {
    protected $table = 'core.payment_method';
    protected $fillable = ['name','description'];
}
