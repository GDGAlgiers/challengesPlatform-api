<?php
namespace App\Http\Repositories;

use App\Http\Resources\SubmissionResource;
use App\Http\Resources\SubmissionResourceForJudge;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

Class SubmissionRepository {

    public function getByTrack() {
        $response = [];
        $submissions = Submission::where('track_id', auth()->user()->track->id)->where('status', 'pending')->whereHas('challenge', function(Builder $query) {
            $query->where('requires_judge', true);
        })->orWhere(function($query) {
            $query->where('status', 'judging')->where('judge_id', auth()->user()->id)->where('track_id', auth()->user()->track_id);
        })->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved all the pending submissions';
        $response['data'] = SubmissionResourceForJudge::collection($submissions);

        return $response;
    }

    public function assignById($id) {
        $response = [];
        $submission = Submission::find($id);
        if($submission->track_id != auth()->user()->track_id) {
            $response['success'] = false;
            $response['message'] = "You can not judge this submission";
            $response['code'] = 403;
            return $response;
        }
        $submission->judge_id = auth()->user()->id;
        $submission->status = "judging";
        $submission->save();
        $response['success'] = true;
        $response['message'] = 'Succefully assigned the submission judgment to you';
        $response['data'] = [];

        return $response;
    }

    public function judgeById($request, $id) {
        $response = [];

        // judgment input is the status general of the judgmentt(approved OR rejected)
        $validator = Validator::make($request->all(), [
            'judgment' => 'required'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation error';
            $response['data'] = $validator->errors();

            return $response;
        }
        $submission = Submission::find($id);
        if($request->judgment == "rejected") {
            $submission->status = "rejected";
            $submission->assigned_points = 0;
            $submission->save();

            // unlock the challenge
            $submission->participant->locks()->detach($submission->challenge_id);
            $submission->participant->save();

            $response['success'] = true;
            $response['message'] = 'Succefully Rejected the submission';
            $response['data'] = [];

            return $response;
        }
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric'
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation error';
            $response['data'] = $validator->errors();

            return $response;
        }

        if($request->points > $submission->challenge->points) {
            $response['success'] = false;
            $response['message'] = 'The given points are greater than the maximaum points of this challenge!';
            $response['data'] =[];
            return $response;
        }

        $prevSubmissions = Submission::where('participant_id', $submission->participant_id)->where('challenge_id', $submission->challenge_id)->pluck('assigned_points')->toArray();

        // We only update the participant's points if the current judge points is greater than all
        // previous assigned points
        if(max($prevSubmissions) < $request->points) {
            $submission->participant->points += ($request->points - max($prevSubmissions));
        }

        $submission->participant->solves()->syncWithoutDetaching([$submission->challenge_id]);

        // we unlock the challenge for the participant if
        // he doesn't reach the max limit of tries
        // AND he didn't get the full points of the challenge yet
        if((count($prevSubmissions) < $submission->challenge->max_tries)&& ($request->points < $submission->challenge->points)) {
            $submission->participant->locks()->detach($submission->challenge_id);
        }
        $submission->participant->save();
        $submission->status = "approved";
        $submission->assigned_points = $request->points;
        $submission->save();
        $response['success'] = true;
        $response['message'] = "Succefully Approved the submission";
        $response['data'] = [];

        return $response;
    }

    public function getAll() {
        $response = [];
        $submissions = Submission::where('participant_id', auth()->user()->id)->get();
        $response['success'] = true;
        $response['data'] = SubmissionResource::collection($submissions);
        $response['message'] = 'Succefully retrieved all previous submissions!';
        return $response;

    }

    public function cancelById($id) {
        $response = [];
        $submission = Submission::find($id);
        $submission->status = "canceled";
        $submission->save();

        $response['success'] = true;
        $response['message'] = 'Submission was successfully canceled!';
        $response['data'] = [];

        return $response;
    }


}
