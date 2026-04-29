<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    protected $table = 'core.grade_level';

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
