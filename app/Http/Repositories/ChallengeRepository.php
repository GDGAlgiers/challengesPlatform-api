<?php
namespace App\Http\Repositories;

use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

Class ChallengeRepository {

    public function get_all() {
        $response = [];
        $challenges = Challenge::all();

        $response['success'] = true;
        $response['data'] = ChallengeResource::collection($challenges);
        $response['message'] = 'Succefully retrieved all the challenges!';

        return $response;
    }

    public function create($request) {
        $response = [];
        $validator = Validator::make($request->all(), [
            'track_id' => 'required|exists:tracks,id',
            'name' => 'required|string',
            'difficulty' => 'required',
            'points' => 'required',
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed';
            $response['data'] = $validator->errors();
            return $response;
        }

        $challenge = Challenge::create([
            'track_id' => $request->track_id,
            'name' => $request->name,
            'difficulty' => $request->difficulty,
            'points' => $request->points,
        ]);

        if($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('challenges/attachments');
            $challenge->attachment = $path;
            $challenge->save();
        }

        $response['success'] = true;
        $response['message'] = 'The challenge was succefully added!';
        $response['data'] = new ChallengeResource($challenge);

        return $response;
    }

    public function update($request, $id) {
        $response = [];
        $challenge = Challenge::find($id);
        if(!$challenge) {
            $response['success'] = false;
            $response['message'] = 'No challenge found!';
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'track_id' => 'required|exists:tracks',
            'name' => 'required|string',
            'difficulty' => 'required',
            'points' => 'required',
        ]);
        $challenge->track_id = $request->track_id;
        $challenge->name = $request->name;
        $challenge->difficulty = $request->difficulty;
        $challenge->points = $request->points;

        if($request->hasFile('attachment')) {
            if($challenge->attachment) Storage::delete($challenge->attachment);
            $path = $request->file('attachment')->store('challenges/attachments');
            $challenge->attachment = $path;
        }

        $challenge->save();

        $response['success'] = true;
        $response['data'] = new ChallengeResource($challenge);
        $response['message'] = 'The challenge was succefully updated!';
        return $response;
    }

    public function delete($id) {
        $response = [];
        $challenge = Challenge::find($id);
        if(!$challenge) {
            $response['success'] = false;
            $response['message'] = 'No challenge found!';
            return $response;
        }
        $challenge->delete();
        $response['success'] = false;
        $response['message'] = 'The challenge was succefully deleted!';
        $response['data'] = [];

        return $response;
    }

    public function submit($request, $id) {
        $response = [];
        $challenge = Challenge::find($id);
        $user = auth()->user();
        $this->incrementTries($user, $id);
        if(!$challenge->requires_judge) {
            $validator = Validator::make($request->all(), [
                'answer' => 'required|string'
            ]);
            if($validator->fails()) {
                $response['success'] = false;
                $response['message'] = 'Validation failed';
                $response['data'] = $validator->errors();
                return $response;
            }

            if($challenge->solution == $request->answer) {
                $this->addSubmission($user, $id, 'Approved');
                $this->challengeSolved($user, $challenge);
                $response['success'] = true;
                $response['message'] = "That's right! you've succefully solved this challenge";
                $response['data'] = [];
                return $response;
            }else {
                $this->addSubmission($user, $id, 'Rejected');
                $response['success'] = false;
                $response['message'] = "That's wrong, think more";
                return $response;
            }
        }else {
            $validator = Validator::make($request->all(), [
                'attachment' => 'required'
            ]);
            if($validator->fails()) {
                $response['success'] = false;
                $response['message'] = 'Validation failed';
                $response['data'] = $validator->errors();
                return $response;
            }

            //locking the challenge's submission until the judge review it
            $user->locks()->attach($id);
            $user->save();
            $this->addSubmission($user, $id, 'Pending', $request->attachment);

            $response['succes'] = true;
            $response['message'] = 'The submission was succefully done, and it is under judgment';
            $response['data'] = [];
            return $response;
        }
    }


    private function incrementTries($participant, $challenge) {
        $participant->submissions()->attach($challenge);
        $participant->save();
        return;
    }

    private function addSubmission($participant, $challenge, $status, $attachment = NULL) {
        $participant->submissions()->attach($challenge, [
            'status' => $status,
            'attachment' => $attachment
        ]);
        $participant->save();
    }

    private function challengeSolved($participant, $challenge) {
        $participant->points += $challenge->points;
        $participant->solves()->attach($challenge->id);
        $participant->locks()->attach($challenge->id);
        $participant->save();
        return;
    }

}
