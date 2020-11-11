<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->header('x-forwarded-proto') <> 'https') && app()->environment() === 'prod') {
            $request->setTrustedProxies([ $request->getClientIp() ]);

            return redirect()->to($request->getRequestUri(), 301, $request->header(), true);
        }

        return $next($request);
    }
}
