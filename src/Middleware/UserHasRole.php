<?php

namespace KissDev\Overseer\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class UserHasRole
 * @package KissDev\Overseer\Middleware
 */
class UserHasRole
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * UserHasRole constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Request filter.
     *
     * @param $request
     * @param Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$this->auth->user()->hasRole($role)) {
            if ($request->ajax()) {
                return response('Unauthorized action.', 401);
            }
            return abort(401);
        }
        return $next($request);
    }
}
