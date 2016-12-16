<?php
namespace KissDev\Overseer\Middleware;

use Closure;
use KissDev\Overseer\Models\Profile;
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

        if ($this->auth->check()) {
            if (!$this->auth->user()->isAuthorized($permissions)) {
                if ($request->ajax()) {
                    return response('Unauthorized action.', 401);
                }
                abort(401, 'Unauthorized action.');
            }
        } else {
            $guest = Profile::whereName('guest')->first();
            if ($guest) {
                if (!$guest->isAuthorized($permissions)) {
                    if ($request->ajax()) {
                        return response('Unauthorized action.', 401);
                    }
                    abort(401, 'Unauthorized action.');
                }
            }else{
                abort(401, 'Unauthorized action.');
            }
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
        $currentRouteAction = class_basename(Route::currentRouteAction());
        if($currentRouteAction == null){
            abort(401, 'Unauthorized action.');
        }
        return $currentRouteAction;

    }
}