<?php

namespace KissDev\Overseer\Traits;

use KissDev\Overseer\Models\Role;
use App\Traits\CacheTrait;

trait OverseerTrait
{
    use CacheTrait;

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function assignRole($roleId)
    {
        $roles = $this->getCacheRelationship('roles', true)->pluck('id');
        if (!$roles->contains($roleId)) {
            $this->flushCache();
            return $this->roles()->attach($roleId);
        }
        return false;
    }

    public function revokeRole($roleId)
    {
        if (!empty($roleId)) {
            $this->flushCache();
            return $this->roles()->detach($roleId);
        }
    }

    public function syncRoles(array $roleId = [])
    {
        if (!empty($roleId)) {
            $this->flushCache();
            return $this->roles()->sync($roleId);
        }
    }

    public function revokeAllRoles()
    {
        $this->flushCache();

        return $this->roles()->detach();
    }

    public function hasRole($name)
    {
        $name = strtolower($name);
        $myRoles = $this->getCacheRelationship('roles', true)->pluck('name')->map(function ($role) {
            return strtolower($role);
        });
        return $myRoles->contains($name);
    }

    public function isAuthorized($permission)
    {
        $myPermissions = $this->getCacheRelationship('permissions');
        return ($myPermissions->contains($permission) || $myPermissions->contains('*'));
    }

    protected function permissions()
    {
        $permissions = collect();
        $this->roles->each(function ($role) use ($permissions) {
            $permissions->push($role->permissions->where('active', true)->pluck('ident')->toArray());
        });
        return $permissions->flatten()->unique();
    }

    public function __call($method, $arguments = [])
    {
        if (starts_with($method, 'is') && $method !== 'is') {
            $role = kebab_case(substr($method, 2));

            return $this->hasRole($role);
        }

        return parent::__call($method, $arguments);
    }
}
