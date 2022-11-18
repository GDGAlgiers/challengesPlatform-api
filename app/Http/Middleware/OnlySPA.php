<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlySPA
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
        $spaUserAgent = config('cors.allowed_user_agent');

        if ($request->userAgent() !== $spaUserAgent) {
            return abort(\Illuminate\Http\Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
