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
            'track_id' => 'required|exists:tracks',
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
}
