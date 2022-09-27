<?php
namespace App\Http\Repositories;

use App\Http\Resources\TrackResource;
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
            'type' => 'required|string',
            'max_earned_points' => 'required'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed';
            $response['data'] = $validator->errors();
            return $response;
        }

        $track = Track::create([
            'type' => $request->type,
            'is_locked' => true,
            'max_earned_points' => $request->max_earned_points
        ]);
        $response['success'] = true;
        $response['message'] = 'Track was succefully created!';
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
    }

}
