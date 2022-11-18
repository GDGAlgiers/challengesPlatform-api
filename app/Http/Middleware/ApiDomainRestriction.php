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
            $allowedHosts = explode(',', env('ALLOWED_DOMAINS'));
            $requestHost = parse_url($request->headers->get('origin'), PHP_URL_HOST);
            if(!in_array($requestHost, $allowedHosts, false))
            {
                return response('', 400);
            }
        }
        return $next($request);
    }
}
