<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavSubmenu extends Model
{
    protected $fillable = [
        'menu_id', 'name', 'search_key', 'to', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(NavMenu::class, 'menu_id');
    }

    public function buttons()
    {
        return $this->hasMany(NavButton::class, 'submenu_id')->orderBy('sort_order');
    }
}
