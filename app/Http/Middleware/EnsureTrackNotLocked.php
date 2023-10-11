<?php

namespace App\Http\Middleware;

use App\Models\Challenge;
use App\Models\Track;
use Closure;
use Illuminate\Http\Request;

class EnsureTrackNotLocked
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
        if($track->is_locked) return response()->json([
            'success' => false,
            'message' => 'The track is locked for now'
        ], 400);
        return $next($request);
    }
}
