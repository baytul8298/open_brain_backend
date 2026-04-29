<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_name',
    ];

    /**
     * Get the permissions that belong to this module.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }

    /**
     * Get the sub-modules that belong to this module.
     */
    public function subModules()
    {
        return $this->hasMany(SubModule::class);
    }
}
