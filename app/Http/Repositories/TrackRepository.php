<?php
namespace App\Http\Repositories;

use App\Http\Resources\ChallengeResource;
use App\Http\Resources\TrackResource;
use App\Http\Resources\User\ParticipantResource;
use App\Models\Track;
use Illuminate\Support\Facades\Validator;

Class TrackRepository {

    public function get_all() {
        $response = [];
        $tracks = Track::all();
        $response['success'] = true;
        $response['message'] = 'Tracks were succefully restored';
        $response['data'] = TrackResource::collection($tracks);
        return $response;
    }

    public function create($request) {
        $response = [];
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:tracks,type',
            'description' => 'required|string'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed';
            $response['data'] = $validator->errors();
            return $response;
        }

        $track = Track::create([
            'type' => $request->type,
            'description' => $request->description,
            'is_locked' => true,
        ]);
        $response['success'] = true;
        $response['message'] = 'Track was succefully created!';
        $response['data'] = new TrackResource($track);
        return $response;
    }

    public function updateById($request, $id) {
        $response = [];
        $validator = Validator::make($request->all(), [
            'description' => 'required|string'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation data failed!';
            $response['data'] = $validator->errors();

            return $response;
        }
        $track = Track::find($id);
        $track->description = $request->description;
        $track->save();
        $response['success'] = true;
        $response['message'] = 'Successfully updated the track!';
        $response['data'] = new TrackResource($track);

        return $response;
    }

    public function lock() {
        $response = [];
        $tracks = Track::all();
        foreach($tracks as $track) {
            $track->is_locked = true;
            $track->save();
        }

        $response['success'] = true;
        $response['data'] = [];
        $response['message'] = 'Tracks were succefully locked';
        return $response;
    }
    public function unlock() {
        $response = [];
        $tracks = Track::all();
        foreach($tracks as $track) {
            $track->is_locked = false;
            $track->save();
        }

        $response['success'] = true;
        $response['data'] = [];
        $response['message'] = 'Tracks were succefully unlocked';
        return $response;
    }

    public function lockById($id) {
        $response = [];
        $track = Track::find($id);
        $track->is_locked = true;
        $track->save();
        $response['success'] = true;
        $response['data'] = [];
        $response['message'] = 'Track was succefully locked';
        return $response;
    }

    public function unlockById($id) {
        $response = [];
        $track = Track::find($id);
        $track->is_locked = false;
        $track->save();
        $response['success'] = true;
        $response['data'] = [];
        $response['message'] = 'Track was succefully unlocked';
        return $response;
    }

    public function delete($id) {
        $response = [];
        $track = Track::find($id);
        if(!$track) {
            $response['success'] = false;
            $response['message'] = 'Track can not be found!';
            return $response;
        }
        $track->delete();
        $response['success'] = true;
        $response['message'] = 'Track was succefully deleted!';
        $response['data'] = [];
        return $response;
    }

    public function get_track_challenges($id) {
        $response = [];
        $track = Track::find($id);
        if($track->is_locked) {
            $response['success'] = false;
            $response['message'] = "The track is locked for now!";

            return $response;
        }
        if($track->id != auth()->user()->track_id) {
            $response['success'] = false;
            $response['message'] = 'You can not get access to this challenge!';
            return $response;
        }
        $challenges = $track->challenges()->where('track_id', auth()->user()->track_id)->get();
        $response['success'] = true;
        $response['message'] = 'Challenges were succefully retrieved!';
        $response['data'] = ChallengeResource::collection($challenges);
        return $response;
    }

    public function getLeaderboardByName($name) {
        $response = [];
        $track = Track::where('type', $name)->first();
        if(!$track) {
            $response['success'] = false;
            $response['message'] = 'Track can not be found!';

            return $response;
        }
        $participants = $track->participants()->where('role', 'participant')->orderBy('points', 'DESC')->orderBy('updated_at', 'ASC')->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved the leaderboard';
        $response['data'] = ParticipantResource::collection($participants);

        return $response;
    }

}
