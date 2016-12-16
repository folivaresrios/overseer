<?php

namespace KissDev\Overseer\Models;

use Config;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Profile
 * @package KissDev\Overseer\Models
 */
class Profile extends Model
{
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
    protected $table = 'profiles';

    /**
     * Profiles can belong to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'))->withTimestamps();
    }

    /**
     * Profiles can belong to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Permission::class);
    }

    /**
     * Get permission "ident" assigned to profile.
     *
     * @return array
     */
    public function getPermissions()
    {
        foreach ($this->permissions as $permission) {
            $myPermissions[] = $permission->ident;
        }

        return $myPermissions;
    }

    /**
     * Checks if the profiles has the given permission.
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
     * Assigns the given permission to the profile.
     *
     * @param null $permissionId
     *
     * @return bool
     */
    public function assignPermission($permissionId = null)
    {
        $permissions = $this->permissions;
        if (!$permissions->contains($permissionId)) {
            return $this->permissions()->attach($permissionId);
        }
    }

    /**
     * Revokes the given permission from the profile.
     *
     * @param null $permissionId
     * @return bool|int
     */
    public function revokePermission($permissionId = null)
    {
        if ($permissionId) {
            return false;
        }
        return $this->permissions()->detach($permissionId);
    }

    /**
     * Syncs the given permission(s) with the profile.
     *
     * @param null $permissionIds
     * @return array
     */
    public function syncPermissions(array $permissionIds = null)
    {
        return $this->permissions()->sync((array)$permissionIds);
    }

    /**
     * Revokes all permissions from the profile.
     *
     * @return int
     */
    public function revokeAllPermissions()
    {
        return $this->permissions()->detach();
    }
}