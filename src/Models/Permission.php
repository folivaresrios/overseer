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
     * @var array
     */
    protected $fillable = ['ident', 'description'];
    /**
     * @var string
     */
    protected $table = 'permissions';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany(\KissDev\Overseer\Models\Profile::class);
    }

    /**
     * @param null $profileId
     * @return bool|void
     */
    public function assignRole($profileId = null)
    {
        $profiles = $this->profiles;
        if (!$profiles->contains($profileId)) {
            return $this->profiles()->attach($profileId);
        }
        return false;
    }

    /**
     * @param null $profileId
     * @return int
     */
    public function revokeProfile($profileId = null)
    {
        return $this->profiles()->detach($profileId);
    }

    /**
     * @param array $profileId
     * @return array
     */
    public function syncProfiles(array $profileId = [])
    {
        return $this->profiles()->sync($profileId);
    }
}