<?php

namespace KissDev\Overseer\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Route;

/**
 * Class UserHasPermission
 * @package KissDev\Overseer\Middleware
 */
class UserHasPermission
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * UserHasPermission constructor.
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
     * @param string $permissions
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permissions = $this->getIdentFromRoute();
        if (!$this->auth->user()->isAuthorized($permissions)) {
            abort(401, 'Unauthorized action.');
        }
        return $next($request);
    }

    /**
     * Get the "ident" from Route, example "HomeController@index"
     *
     * @return string
     */
    private function getIdentFromRoute()
    {
        $currentRouteAction = Route::currentRouteAction();
        if ($currentRouteAction == null) {
            abort(401, 'The route dont have Controllers');
        }
        return $currentRouteAction;
    }
}
