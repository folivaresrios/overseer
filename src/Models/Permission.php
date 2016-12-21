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
     * Permissions can belong to many profiles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Profile::class);
    }

    /**
     * Assigns the given profile to the permission.
     *
     * @param null $profileId
     */
    public function assignProfile($profileId = null)
    {
        $profiles = $this->profiles;
        if (!$profiles->contains($profileId)) {
            return $this->profiles()->attach($profileId);
        }
    }

    /**
     * Revokes the given profile from the permission.
     *
     * @param null $profileId
     * @return int
     */
    public function revokeProfile($profileId = null)
    {
        if(!empty($profileId)){
            return $this->profiles()->detach((array)$profileId);
        }
    }

    /**
     * Syncs the given profile(s) with the permission.
     *
     * @param null $profileId
     * @return array
     */
    public function syncProfiles($profileId = null)
    {
        if(!empty($profileId)){
            return $this->profiles()->sync((array)$profileId);
        }
    }

    /**
     * Revokes all profiles from the permission.
     *
     * @return int
     */
    public function revokeAllProfiles()
    {
        return $this->profiles()->detach();
    }
}