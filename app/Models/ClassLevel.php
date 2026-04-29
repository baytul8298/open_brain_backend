<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    protected $table = 'core.class_names';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];
}
