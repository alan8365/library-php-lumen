<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class RoleBasedAccessControlMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if ($request->user()->checkPermission($permission)) {
            return $next($request);
        } else {
            return response('Unauthorized.', 401);
        }
    }
}
