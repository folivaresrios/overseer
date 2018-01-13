<?php

namespace KissDev\Overseer\Traits;

use KissDev\Overseer\Models\Permission;

/**
 * Class OverseerTrait
 * @package KissDev\Overseer\Traits
 */
trait OverseerTrait
{
    use CacheTrait;

    /**
     * Return cache tag used by the user model.
     * @return string
     */
    public function getCacheTag()
    {
        return 'users';
    }

    /**
     * Users can belong to many roles.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Role::class)->withTimestamps();
    }

    public function permissions()
    {
        return Permission::find($this->roles()->pluck('roles.id'))->where('active', true)->pluck('ident')->toArray();
    }

    /**
     * Get all user roles.
     *
     * @param string $column
     * @return mixed
     */
    public function getRoles($column = 'name')
    {
        if (!is_null($this->roles)) {
            return $this->roles->pluck($column)->all();
        }
    }

    /**
     * Assigns the given role to the user.
     *
     * @param null $roleId
     * @return bool|void
     */
    public function assignRole($roleId)
    {
        $this->flushPermissionCache();

        $roles = collect($this->getRoles('id'));
        if (!$roles->contains($roleId)) {
            return $this->roles()->attach($roleId);
        }
        return false;
    }

    /**
     * Revokes the given role from the user.
     *
     * @param null $roleId
     * @return int
     */
    public function revokeRole($roleId)
    {
        $this->flushPermissionCache();

        if ($roleId != null) {
            return $this->roles()->detach($roleId);
        }
    }

    /**
     * Syncs the given role(s) with the user.
     *
     * @param array $roleId
     *
     * @return bool
     */
    public function syncRoles($roleId)
    {
        $this->flushPermissionCache();
        return $this->roles()->sync($roleId);
    }

    /**
     * Revokes all roles from the user.
     *
     * @return int
     */
    public function revokeAllRoles()
    {
        $this->flushPermissionCache();

        return $this->roles()->detach();
    }

    /**
     * Checks if the user has the given role.
     *
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        $name = strtolower($name);
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if permission is active.
     *
     * @param $permission
     * @return bool
     */
    public function isAuthorized($permission) //isAuthorized
    {
        $myPermissions = collect($this->getPermissions());
        if ($myPermissions->isEmpty()) {
            throw new \Exception("The permission: $permission does not exit");
        }
        return ($myPermissions->contains($permission) || $myPermissions->contains('*'));
    }

    /**
     * Get all user role permissions fresh from database
     *
     * @return array|null
     */
    protected function getFreshPermissions()
    {
        $permissions[] = $this->permissions();
        foreach ($this->roles as $role) {
            $permissions[] = $role->getFreshPermissions();
        }
        return $this->filterUniqueArray($permissions);
    }

    private function filterUniqueArray($array)
    {
        return array_values(array_unique(call_user_func_array('array_merge', $array)));
    }
}
