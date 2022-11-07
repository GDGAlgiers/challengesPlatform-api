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
        // judgment is the status general of the judge(approved OR rejected)
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
            $response['status'] = true;
            $response['message'] = 'Succefully Rejected the submission';
            $response['data'] = [];

            // unlock the challenge
            $submission->participant->locks()->detach($submission->challenge_id);
            $submission->participant->save();
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
            $response['message'] = 'Given points are greater than the maximaum of this challenge!';
            $response['data'] =[];
            return $response;
        }

        $submission->participant->points += $request->points;
        $submission->participant->solves()->attach($submission->challenge_id);
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
        $response['data'] = new SubmissionResource($submission);

        return $response;
    }

}
