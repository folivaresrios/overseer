<?php

namespace KissDev\Overseer;

use Illuminate\Contracts\Auth\Guard;
use KissDev\Overseer\Models\Profile;


/**
 * Class Overseer
 * @package KissDev\Overseer
 */
class Overseer
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * Overseer constructor.
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->auth = $guard;
    }

    /**
     * Checks if user has the given permissions.
     *
     * @param $permissions
     * @return bool
     */
    public function isAuthorized($permissions)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->isAuthorized($permissions);
        } else {
            $guest = Profile::whereName('guest')->first();
            if ($guest) {
                return $guest->isAuthorized($permissions);
            }
        }
        return false;
    }

    /**
     * Checks if user is assigned the given profile.
     *
     * @param $profile
     * @return bool
     */
    public function hasProfile($profile)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->hasProfile($profile);
        }
        return false;
    }
}