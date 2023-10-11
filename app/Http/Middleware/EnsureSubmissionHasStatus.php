<?php

namespace App\Http\Middleware;

use App\Models\Submission;
use Closure;
use Illuminate\Http\Request;

class EnsureSubmissionHasStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $status)
    {
        $submission = Submission::find($request->route('id'));
        if(!$submission->hasStatus($status)) return response()->json([
            'success' => false,
            'message' => 'This action can be made for submissions that have status '.$status
        ], 403);
        return $next($request);
    }
}
