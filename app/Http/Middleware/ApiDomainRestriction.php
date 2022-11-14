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
        if(env('APP_ENV') == "production") {
            if(in_array($request->getHost(), ['devfest22-challenges.gdgalgiers.com']) == false)
            {
                return response('', 400);
            }
        }
        return $next($request);
    }
}
