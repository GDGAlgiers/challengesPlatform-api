<?php

namespace App\Http\Middleware;

use App\Models\Submission;
use Closure;
use Illuminate\Http\Request;

class EnsureValidSubmissionToJudge
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

        // the following check should never happen, but still it's better to check
        // in case of database corruption
        if($submission->track_id !== auth()->user()->track_id || !$submission->challenge->requires_judge) {
            return response()->json([
                'success' => false,
                'message' => 'You can not judge this submission'
            ], 403);
        }

        // the following check should never happen, but still it's better to check
        // in case of database corruption
        if(!$submission->attachment) {
            return response()->json([
                'success' => false,
                'message' => 'This submission misses attachment and it can not be judged, contact the admin'
            ], 500);
        }

        if($submission->judge_id != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'This submission is being reviewd by another judge already'
            ], 403);
        }
        return $next($request);
    }
}
