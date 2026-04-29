<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavParentMenu extends Model
{
    protected $fillable = [
        'name', 'type', 'guard', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function menus()
    {
        return $this->hasMany(NavMenu::class, 'parent_menu_id')->orderBy('sort_order');
    }
}
