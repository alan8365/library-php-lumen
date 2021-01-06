<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeforeAnyDbQueryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DB::enableQueryLog();
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::info('db', DB::getQueryLog());
    }
}
