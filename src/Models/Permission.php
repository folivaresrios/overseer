<?php

namespace KissDev\Overseer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package KissDev\Overseer\Models
 */
class Permission extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['ident', 'description', 'active'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Permissions can belong to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Role::class);
    }

    /**
     * Assigns the given role to the permission.
     *
     * @param null $roleId
     */
    public function assignRole($roleId = null)
    {
        $roles = $this->roles;
        if (!$roles->contains($roleId)) {
            return $this->roles()->attach($roleId);
        }
    }

    /**
     * Revokes the given role from the permission.
     *
     * @param null $roleId
     * @return int
     */
    public function revokeRole($roleId = null)
    {
        if (!empty($roleId)) {
            return $this->roles()->detach((array)$roleId);
        }
    }

    /**
     * Syncs the given role(s) with the permission.
     *
     * @param null $roleId
     * @return array
     */
    public function syncRoles($roleId = null)
    {
        if (!empty($roleId)) {
            return $this->roles()->sync((array)$roleId);
        }
    }

    /**
     * Revokes all roles from the permission.
     *
     * @return int
     */
    public function revokeAllRoles()
    {
        return $this->roles()->detach();
    }
}
