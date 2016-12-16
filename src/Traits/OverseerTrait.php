<?php

namespace KissDev\Overseer\Traits;

/**
 * Class OverseerTrait
 * @package KissDev\Overseer\Traits
 */
/**
 * Class OverseerTrait
 * @package KissDev\Overseer\Traits
 */
trait OverseerTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Profile::class);
    }

    /**
     * @param string $column
     * @return mixed
     */
    public function getProfiles($column = 'name')
    {
        if ($this->profiles) {
            return $this->profiles->pluck($column)->all();
        }
    }

    /**
     * @param null $roleId
     * @return bool|void
     */
    public function assignProfile($roleId = null)
    {
        $profile = $this->getProfiles('id');
        if (!$profile->contains($roleId)) {
            return $this->profiles()->attach($roleId);
        }
        return false;
    }

    /**
     * @param null $roleId
     * @return int
     */
    public function revokeProfile($roleId = null)
    {
        return $this->profiles()->detach($roleId);
    }

    /**
     * @return int
     */
    public function revokeAllProfiles()
    {
        return $this->profiles()->detach();
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        $permissions = [[], []];
        foreach ($this->profiles as $profile) {
            $permissions[] = $profile->getPermissions();
        }
        return call_user_func_array('array_merge', $permissions);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function isAuthorized($permission)
    {
        foreach ($this->profiles as $profile) {
            $myPermissions = $profile->permissions->pluck('ident')->all();
            if ($myPermissions->contains($permission) || $myPermissions->contains('*')) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProfile($name)
    {
        $name = strtolower($name);
        foreach ($this->profiles as $profile) {
            if ($profile->name == $name) {
                return true;
            }
        }
        return false;
    }

}