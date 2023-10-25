<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiDomainRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = [env('FRONT_URL')];
        $requestOrigin = $request->headers->get('Origin');
        if(!in_array($requestOrigin, $allowedOrigins, false))
        {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
