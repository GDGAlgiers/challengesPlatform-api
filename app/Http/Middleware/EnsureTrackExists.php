<?php

namespace App\Http\Middleware;

use App\Models\Track;
use Closure;
use Illuminate\Http\Request;

class EnsureTrackExists
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
        $track = Track::find($request->route('id'));
        if(!$track) return response()->json([
            'success' => false,
            'message' => 'Track can not be found!'
        ]);
        return $next($request);
    }
}
