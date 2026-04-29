<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavButton extends Model
{
    protected $fillable = [
        'submenu_id', 'name', 'key_value', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function submenu()
    {
        return $this->belongsTo(NavSubmenu::class, 'submenu_id');
    }
}
