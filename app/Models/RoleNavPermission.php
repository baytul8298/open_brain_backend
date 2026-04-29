<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleNavPermission extends Model
{
    protected $fillable = [
        'role_id', 'nav_item_type', 'nav_item_id',
    ];

    protected $casts = [
        'role_id'     => 'integer',
        'nav_item_id' => 'integer',
    ];
}
