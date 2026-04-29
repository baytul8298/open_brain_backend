<?php
namespace App\Models\Commerce;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'commerce.wallet_transactions';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['teacher_id','payment_id','payout_id','type','amount','balance_after','description'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id', 'id'); }
    public function payment() { return $this->belongsTo(Payment::class, 'payment_id', 'id'); }
    public function payout()  { return $this->belongsTo(Payout::class, 'payout_id', 'id'); }
}
