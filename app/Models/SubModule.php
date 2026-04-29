<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'name',
    ];

    /**
     * Get the module that owns the sub-module.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the permissions that belong to this sub-module.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'sub_module_id');
    }
}
