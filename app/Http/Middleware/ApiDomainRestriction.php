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
        $allowedOrigins = ['https://flutter-forward-extended-2023-challenges.gdgalgiers.com'];
        $requestOrigin = $request->headers->get('Origin');
        $allowedIps = ['105.106.171.160'];
        if(!in_array($requestOrigin, $allowedOrigins, false) && !in_array($request->ip(), $allowedIps))
        {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
