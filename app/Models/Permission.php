<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'module_id',
        'sub_module',
        'sub_module_id',
    ];

    /**
     * Get the module that owns the permission.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the sub-module that owns the permission.
     * Named subModuleRelation to avoid JSON key conflict with the legacy sub_module string column.
     */
    public function subModuleRelation()
    {
        return $this->belongsTo(SubModule::class, 'sub_module_id');
    }
}
