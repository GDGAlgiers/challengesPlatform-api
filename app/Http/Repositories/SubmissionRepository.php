<?php
namespace App\Http\Repositories;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Support\Facades\Validator;

Class SubmissionRepository {

    public function getByTrack() {
        $response = [];
        $submissions = Submission::where('track_id', auth()->user()->track)->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved all the submissions';
        $response['data'] = SubmissionResource::collection($submissions);

        return $response;
    }

    public function judgeById($request, $id) {
        $response = [];
        // judgment is the pourcentage of all possible points (from 0 to 1)
        $validator = Validator::create($request->all(), [
            'judgment' => 'required'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation error';
            $response['data'] = $validator->errors();

            return $response;
        }
        $submission = Submission::find($id);
        if($request->judgment == "Rejected") {
            $submission->status = "Rejected";
            $submission->save();
            $response['status'] = true;
            $response['message'] = 'Succefully Rejected the submission';
            $response['data'] = [];

            // unlock the challenge
            $submission->participant->locks()->detach($submission->challenge_id);
            $submission->participant->save();
            return $response;
        }

        $submission->participant->points += ($submission->challenge->points * $request->judgment);
        $submission->participant->solves()->attach($submission->challenge_id);
        $submission->participant->save();
        $submission->status = "Approved";

        $response['success'] = true;
        $response['message'] = "Succefully Approved the submission";
        $response['data'] = [];

        return $response;
    }

}
