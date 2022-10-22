<?php

namespace App\Http\Middleware;

use App\Models\Challenge;
use Closure;
use Illuminate\Http\Request;

class EnsureChallengeNotLocked
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
        $challenge = Challenge::find($request->route('id'));
        if($challenge->is_locked) return response()->json([
            'success' => false,
            'message' => 'This challenge is locked for now'
        ]);
        return $next($request);
    }
}
