<?php

namespace KissDev\Overseer\Models;

use Config;
use Illuminate\Database\Eloquent\Model;
use KissDev\Overseer\Traits\CacheTrait;

/**
 * Class Role
 * @package KissDev\Overseer\Models
 */
class Role extends Model
{
    use CacheTrait;
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The Overseer cache tag used by the model.
     *
     * @return string
     */
    public static function getCacheTag()
    {
        return 'roles';
    }

    /**
     * Roles can belong to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'))->withTimestamps();
    }

    /**
     * Roles can belong to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Permission::class)->withTimestamps();
        ;
    }

    /**
     * Checks if the roles has the given permission.
     *
     * @param $permission
     * @return bool
     */
    public function isAuthorized($permission)
    {
        $myPermissions = $this->getPermissions();
        return (in_array($permission, $myPermissions) || in_array('*', $myPermissions));
    }

    /**
     * Assigns the given permission to the role.
     *
     * @param null $permissionId
     *
     * @return bool
     */
    public function assignPermission($permissionId = null)
    {
        $permissions = $this->permissions;
        if (!$permissions->contains($permissionId)) {
            $this->flushPermissionCache();

            return $this->permissions()->attach($permissionId);
        }
        return false;
    }

    /**
     * Revokes the given permission from the role.
     *
     * @param null $permissionId
     * @return bool|int
     */
    public function revokePermission($permissionId = null)
    {
        if ($permissionId) {
            return false;
        }
        $this->flushPermissionCache();

        return $this->permissions()->detach($permissionId);
    }

    /**
     * Syncs the given permission(s) with the role.
     *
     * @param null $permissionIds
     * @return array
     */
    public function syncPermissions(array $permissionIds = null)
    {
        $this->flushPermissionCache();

        return $this->permissions()->sync((array)$permissionIds);
    }

    /**
     * Revokes all permissions from the role.
     *
     * @return int
     */
    public function revokeAllPermissions()
    {
        $this->flushPermissionCache();

        return $this->permissions()->detach();
    }

    /**
     * Get fresh permission slugs assigned to role from database.
     *
     * @return array
     */
    public function getFreshPermissions()
    {
        return $this->permissions->where('active', true)->pluck('ident')->all();
    }
}
