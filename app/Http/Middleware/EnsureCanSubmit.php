<?php

namespace App\Http\Middleware;

use App\Models\Challenge;
use App\Models\Submission;
use Closure;
use Illuminate\Http\Request;

class EnsureCanSubmit
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

        // Verifying if the submitor has reached  the challenge's submissions limit
        $submissions = Submission::where('participant_id', auth()->user()->id)->where('challenge_id', $request->route('id'))->get();
        if(count($submissions) >= $challenge->max_tries) {
            return response()->json([
                'success' => false,
                'message' => 'You reached your submissions limit for this challenge'
            ], 403);
        }

        // Verifying if the challenge is locked for the submitor
        $challengesLocked = auth()->user()->locks->pluck('id')->toArray();
        if(in_array($request->route('id'), $challengesLocked)) {
            return response()->json([
                'success' => false,
                'message' => "This challenge is locked for you, either it's under judgment or you already solved it"
            ], 403);
        }

        if($challenge->track_id != auth()->user()->track_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can not submit for this challenge'
            ], 403);
        }
        return $next($request);
    }
}
