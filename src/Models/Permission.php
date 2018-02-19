<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use KissDev\Overseer\Traits\CacheTrait;

class Permission extends Model
{
    use CacheTrait;

    protected $fillable = ['ident', 'name', 'active'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function assignRole($roleId = null)
    {
        $roles = $this->getCacheRelationship('roles', true)->pluck('id');
        if (!$roles->contains($roleId)) {
            $this->flushCache();
            return $this->roles()->attach($roleId);
        }
    }

    public function revokeRole($roleId = null)
    {
        if (!empty($roleId)) {
            $this->flushCache();
            return $this->roles()->detach((array)$roleId);
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
}
