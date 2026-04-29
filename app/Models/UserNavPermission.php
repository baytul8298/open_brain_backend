<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNavPermission extends Model
{
    protected $fillable = [
        'user_id', 'nav_item_type', 'nav_item_id', 'is_granted',
    ];

    protected $casts = [
        'nav_item_id' => 'integer',
        'is_granted'  => 'boolean',
    ];
}
