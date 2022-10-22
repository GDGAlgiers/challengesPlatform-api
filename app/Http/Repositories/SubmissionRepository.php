<?php
namespace App\Http\Repositories;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

Class SubmissionRepository {

    public function getByTrack() {
        $response = [];
        $submissions = Submission::where('track_id', auth()->user()->track->id)->where('status', 'pending')->whereHas('challenge', function(Builder $query) {
            $query->where('requires_judge', true);
        })->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved all the submissions';
        $response['data'] = SubmissionResource::collection($submissions);

        return $response;
    }

    public function assignById($id) {
        $response = [];
        $submission = Submission::find($id);
        $submission->status = "judging";
        $submission->save();
        $response['success'] = true;
        $response['message'] = 'Succefully assigning submission';
        $response['data'] = new SubmissionResource($submission);

        return $response;
    }

    public function judgeById($request, $id) {
        $response = [];
        // judgment is the pourcentage of all possible points (from 0 to 1)
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
            $submission->save();
            $response['status'] = true;
            $response['message'] = 'Succefully Rejected the submission';
            $response['data'] = [];

            // unlock the challenge
            $submission->participant->locks()->detach($submission->challenge_id);
            $submission->participant->save();
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|between:0,1'
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation error';
            $response['data'] = $validator->errors();

            return $response;
        }

        $submission->participant->points += ($submission->challenge->points * $request->points);
        $submission->participant->solves()->attach($submission->challenge_id);
        $submission->participant->save();
        $submission->status = "approved";
        $submission->save();
        $response['success'] = true;
        $response['message'] = "Succefully Approved the submission";
        $response['data'] = [];

        return $response;
    }

}
