<?php
namespace App\Http\Repositories;

use App\Http\Resources\ChallengeResource;
use App\Http\Resources\SubmissionResource;
use App\Models\Challenge;
use App\Models\Submission;
use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'track' => 'required|exists:tracks,type',
            'name' => 'required|string',
            'author' => 'required|string',
            'difficulty' => 'required',
            'description' => 'required',
            'max_tries' => 'required|integer',
            'requires_judge' => 'required',
            'points' => 'required',
            'attachment' => 'nullable|mimes:zip,pdf,txt|max:2024'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed';
            $response['data'] = $validator->errors();
            return $response;
        }

        $trackID = Track::where('type', $request->track)->pluck('id')->first();
        $challenge = Challenge::create([
            'track_id' => $trackID,
            'name' => $request->name,
            'author' => $request->author,
            'difficulty' => $request->difficulty,
            'description' => $request->description,
            'step' => $request->step,
            'max_tries' => $request->max_tries,
            'external_resource' => $request->external_resource,
            'requires_judge' => $request->requires_judge,
            'solution' => Hash::make($request->solution),
            'points' => $request->points,
            'is_locked' => false
        ]);
        if($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store("challenges_attachments");
            $challenge->attachment = $path;
            $challenge->save();
        }

        $response['success'] = true;
        $response['message'] = 'The challenge was succefully added!';
        $response['data'] = new ChallengeResource($challenge);

        return $response;
    }

    public function getById($id) {
        $response = [];
        $challenge = Challenge::find($id);
        if($challenge->track_id !== auth()->user()->track_id) {
            $response['success'] = false;
            $response['message'] = 'You can not view this challenge!';
            return $response;
        }
        $response['success'] = true;
        $response['data'] = new ChallengeResource($challenge);
        $response['message'] = 'Succefully retrieved the challenge';
        return $response;
    }

    public function update($request, $id) {
        $response = [];
        $challenge = Challenge::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'author' => 'required|string',
            'difficulty' => 'required',
            'description' => 'required',
            'max_tries' => 'required|integer',
            'points' => 'required',
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed';
            $response['data'] = $validator->errors();
            return $response;
        }

        $challenge->name = $request->name;
        $challenge->author = $request->author;
        $challenge->difficulty = $request->difficulty;
        $challenge->description = $request->description;
        $challenge->max_tries = $request->max_tries;
        $challenge->points = $request->points;
        if($request->solution) $challenge->solution = Hash::make($request->solution);
        if($request->external_resource) $challenge->external_resource = $request->external_resource;

        if($request->hasFile('attachment')) {
            if($challenge->attachment) Storage::delete($challenge->attachment);
            $path = $request->file('attachment')->store('challenges_attachments');
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

        if($challenge->attachment) Storage::delete($challenge->attachment);
        $challenge->delete();

        $response['success'] = true;
        $response['message'] = 'The challenge was succefully deleted!';
        $response['data'] = [];

        return $response;
    }

    public function lockById($id) {
        $response = [];
        $challenge = Challenge::find($id);

        // We lock directly because the challenge should be not locked
        // as this request already passed the middleware `challengeNotLocked`
        $challenge->is_locked = true;
        $challenge->save();
        $response['success'] = true;
        $response['message'] = 'Challenge Succefully locked!';
        $response['data'] = [];

        return $response;
    }
    public function unlockById($id) {
        $response = [];
        $challenge = Challenge::find($id);
        if(!$challenge->is_locked) {
            $response['success'] = false;
            $response['message'] = 'Challenge is already unlocked!';
            return $response;
        }
        $challenge->is_locked = false;
        $challenge->save();
        $response['success'] = true;
        $response['message'] = 'Challenge Succefully unlocked!';
        $response['data'] = [];

        return $response;
    }
    public function submit($request, $id) {
        $response = [];
        $challenge = Challenge::find($id);
        $user = auth()->user();
        if(!$challenge->requires_judge) {
            $validator = Validator::make($request->all(), [
                'answer' => 'required|string'
            ]);
            if($validator->fails()) {
                $response['success'] = false;
                $response['message'] = 'Validation failed, inputs missed';
                $response['data'] = $validator->errors();
                return $response;
            }

            if(Hash::check($request->answer, $challenge->solution)) {
                $this->addSubmission($id, $challenge->track->id, 'Approved', NULL, $challenge->points);
                $this->challengeSolved($user, $challenge);
                $response['success'] = true;
                if(auth()->user()->step > $challenge->track->challenges()->count()) {
                    $numOfWinners = User::where('is_member', false)->where('step', '>', count(Challenge::all()))->count();
                    if($numOfWinners <= 3 && !auth()->user()->is_member) {
                        $goldenTicket = 'GDGAlgiers'.Str::random(6).'WelcomeDay22';
                        auth()->user()->golden_ticket = $goldenTicket;
                        auth()->user()->save();
                        $response['message'] = "Congrats! you've won the challenge!";
                        $response['data'] = $goldenTicket;
                    }else {
                        $response['message'] = "Congrats! you've won the challenge! but there are others who came first :)";
                        $response['data'] = [];
                    }
                }else {
                    $response['message'] = "That's right! you've succefully solved this challenge";
                    $response['data'] = [];
                }
                return $response;
            }else {
                $this->addSubmission($id, $challenge->track->id, 'Rejected', NULL, 0);
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
                $response['message'] = 'Validation failed, inputes missed';
                $response['data'] = $validator->errors();
                return $response;
            }

            //locking the challenge's submission until the judge reviewes it
            $user->locks()->attach($id);
            $user->save();
            $this->addSubmission($id, $challenge->track->id, 'Pending', $request->attachment);

            $response['success'] = true;
            $response['message'] = 'The submission was succefully done, and it is under judgment';
            $response['data'] = [];
            return $response;
        }
    }

    public function getSubmissionsById($id) {
        $response = [];
        $submissions = Submission::where('challenge_id', $id)->where('participant_id', auth()->user()->id)->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved all submissions';
        $response['data'] = SubmissionResource::collection($submissions);

        return $response;

    }

    private function addSubmission($challengeID,$trackID,  $status, $attachment = NULL, $points = NULL) {
        Submission::create([
            'participant_id' => auth()->user()->id,
            'challenge_id' => $challengeID,
            'track_id' => $trackID,
            'attachment' => $attachment,
            'status' => $status,
            'assigned_points' => $points
        ]);
    }

    private function challengeSolved($participant, $challenge) {
        $participant->points += $challenge->points;
        $participant->step +=1;
        $participant->solves()->attach($challenge->id);
        $participant->locks()->attach($challenge->id);
        $participant->save();
        return;
    }

}
