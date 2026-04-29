<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavMenu extends Model
{
    protected $fillable = [
        'parent_menu_id', 'name', 'type', 'key', 'icon_key', 'to', 'search_key',
        'divider_title', 'guard', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'parent_menu_id' => 'integer',
    ];

    public function parentMenu()
    {
        return $this->belongsTo(NavParentMenu::class, 'parent_menu_id');
    }

    public function submenus()
    {
        return $this->hasMany(NavSubmenu::class, 'menu_id')->orderBy('sort_order');
    }
}
