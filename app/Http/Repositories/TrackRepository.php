<?php
namespace App\Http\Repositories;

use App\Http\Resources\ChallengeResource;
use App\Http\Resources\TrackResource;
use App\Http\Resources\User\ParticipantResource;
use App\Http\Resources\User\ParticipantResourceWithSubmissions;
use App\Models\Track;
use Illuminate\Support\Facades\Validator;

Class TrackRepository {

    public function get_all() {
        $response = [];
        $tracks = Track::all();
        $response['success'] = true;
        $response['message'] = 'Tracks were succefully retreived';
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
        $track->delete();
        $response['success'] = true;
        $response['message'] = 'Track was succefully deleted!';
        $response['data'] = [];
        return $response;
    }

    public function get_track_challenges($id) {
        $response = [];
        $track = Track::find($id);

        $response['success'] = true;
        $response['message'] = 'Challenges were succefully retrieved!';
        $response['data'] = ChallengeResource::collection($track->challenges);
        return $response;
    }

    public function getLeaderboardByName($type) {
        $response = [];
        $track = Track::where('type', $type)->first();
        if(!$track) {
            $response['success'] = false;
            $response['message'] = 'Track can not be found!';

            return $response;
        }
        $participants = $track->participants()->where('role', 'participant')->orderBy('points', 'DESC')->orderBy('updated_at', 'ASC')->get();
        $response['success'] = true;
        $response['message'] = 'Succefully retrieved the leaderboard';
        $response['data'] = ParticipantResourceWithSubmissions::collection($participants);

        return $response;
    }

    public function getTrackTypes() {
        $response = [];

        $tracksTypes = Track::all()->pluck('type');
        $response["success"] = true;
        $response["message"] = "Successfully retrieved all tracks types";
        $response["data"] = $tracksTypes;

        return $response;
    }

}
