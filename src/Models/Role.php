<?php

namespace KissDev\Overseer\Models;

use KissDev\Overseer\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use CacheTrait;

    protected $fillable = ['name', 'description'];

    protected $with = ['permissions'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
        ;
    }

    public function assignPermission($permissionId = null)
    {
        $permissions = $this->getCacheRelationship('permissions', true)->pluck('id');
        if (!$permissions->contains($permissionId)) {
            $this->flushCache();

            return $this->permissions()->attach($permissionId);
        }
        return false;
    }

    public function revokePermission($permissionId = null)
    {
        if (!empty($permissionId)) {
            $this->flushCache();

            return $this->permissions()->detach($permissionId);
        }
    }

    public function syncPermissions($permissionIds = null)
    {
        if (!empty($permissionId)) {
            $this->flushCache();

            return $this->permissions()->sync((array)$permissionIds);
        }
    }

    public function revokeAllPermissions()
    {
        $this->flushCache();

        return $this->permissions()->detach();
    }
}
