<?php

namespace App\Http\Middleware;

use App\Models\Submission;
use Closure;
use Illuminate\Http\Request;

class EnsureSubmissionExists
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
        $submission = Submission::find($request->route('id'));
        if(!$submission) return response()->json([
            'success' => false,
            'message' => 'Submission can not be found!'
        ], 404);
        return $next($request);
    }
}
