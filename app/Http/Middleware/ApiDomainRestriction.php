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
        $allowedHosts = explode(',', env('ALLOWED_DOMAINS'));
        $requestHost = $request->getHost();

        $allowedIps = ['105.106.171.160', '129.45.74.135'];
        if(!in_array($requestHost, $allowedHosts) && !in_array($request->ip(), $allowedIps))
        {
            return response('Unauthorized', 401);
        }
        return $next($request);
    }
}
