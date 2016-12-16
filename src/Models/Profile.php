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
     * @var array
     */
    protected $fillable = ['name', 'description'];
    /**
     * @var string
     */
    protected $table = 'profiles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'))->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Permission::class);
    }

    /**
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
     * @param $permission
     * @return bool
     */
    public function isAuthorized($permission)
    {
        $myPermissions = $this->getPermissions();
        return (in_array($permission, $myPermissions) || in_array('*', $myPermissions));
    }

    /**
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
     * @param array $permissionIds
     * @return array
     */
    public function syncPermissions(array $permissionIds = [])
    {
        return $this->permissions()->sync($permissionIds);
    }

    /**
     * @return int
     */
    public function revokeAllPermissions()
    {
        return $this->permissions()->detach();
    }
}