<?php

namespace KissDev\Overseer;

use Illuminate\Contracts\Auth\Guard;
use KissDev\Overseer\Models\Role;

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
        }
        return false;
    }

    /**
     * Checks if user is assigned the given profile.
     *
     * @param $profile
     * @return bool
     */
    public function hasRole($profile)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->hasRole($profile);
        }
        return false;
    }
}
