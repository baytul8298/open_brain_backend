<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'role_type',
        'display_name',
        'description',
    ];

    /**
     * Get all permissions grouped by module.
     */
    public function getPermissionsGroupedByModule()
    {
        return $this->permissions()
            ->with('module')
            ->get()
            ->groupBy(function ($permission) {
                return $permission->module ? $permission->module->module_name : 'Ungrouped';
            });
    }
}
