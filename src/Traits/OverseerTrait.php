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
     * Users can belong to many profiles.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Profile::class);
    }

    /**
     * Get all user profiles.
     *
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
     * Assigns the given profile to the user.
     *
     * @param null $profileId
     * @return bool|void
     */
    public function assignProfile($profileId = null)
    {
        $profile = $this->getProfiles('id');
        if (!$profile->contains($profileId)) {
            return $this->profiles()->attach($profileId);
        }
        return false;
    }

    /**
     * Revokes the given profile from the user.
     *
     * @param null $profileId
     * @return int
     */
    public function revokeProfile($profileId = null)
    {
        return $this->profiles()->detach($profileId);
    }

    /**
     * Syncs the given profile(s) with the user.
     *
     * @param array $profileId
     *
     * @return bool
     */
    public function syncRoles(array $profileId)
    {
        return $this->profiles()->sync($profileId);
    }

    /**
     * Revokes all profiles from the user.
     *
     * @return int
     */
    public function revokeAllProfiles()
    {
        return $this->profiles()->detach();
    }

    /**
     * Get all user profile permissions.
     *
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
     * Check if user has the given permission.
     *
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
     * Checks if the user has the given profile.
     *
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