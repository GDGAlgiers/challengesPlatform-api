<?php
namespace App\Http\Repositories;

use App\Http\Resources\User\JudgeResource;
use App\Http\Resources\User\ParticipantResource;
use App\Http\Resources\User\UserResource;
use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantAccountCreated;
use App\Mail\JudgeAccountCreated;

Class UserRepository {

    public function getAll() {
        $response = [];
        $users = User::all();
        $response['success'] = true;
        $response['data'] = UserResource::collection($users);
        $response['message'] = 'Successfully retrieved all the users!';
        return $response;
    }
    public function create_participant($request) {
        $response = [];
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|unique:users,full_name',
            'password' => 'required|min:6',
            'track' => 'required|exists:tracks,type'
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed!';
            $response['data'] = $validator->errors();
            return $response;
        }
        $trackID = Track::where('type', $request->track)->pluck('id')->first();
        $user = User::create([
            'full_name' => $request->full_name,
            'track_id' => $trackID,
            'password' => Hash::make($request->password),
            'role' => 'participant',
            'points' => 0
        ]);
        $response['success'] = true;
        $response['message'] = 'Participant was succefully created!';
        $response['data'] = new ParticipantResource($user);
        return $response;
    }

    public function create_judge($request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|unique:users,full_name',
            'password' => 'required|string|min:6',
            'track' => 'required|exists:tracks,type'
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation errors!';
            $response['data'] = $validator->errors();
            return $response;
        }
        $trackID = Track::where('type', $request->track)->pluck('id')->first();
        $user = User::create([
            'full_name' => $request->full_name,
            'password' => Hash::make($request->password),
            'role' => 'judge',
            'track_id' => $trackID
        ]);
        $response['success'] = true;
        $response['data'] = new JudgeResource($user);
        $response['message'] = 'Succefully registred the judge!';
        return $response;
    }

    public function delete_user($id) {
        $response = [];

        $user = User::find($id);
        if(!$user) {
            $response['success'] = false;
            $response['message'] = 'User can not be found!';
            return $response;
        }
        $user->delete();
        $response['success'] = true;
        $response['message'] = 'The user was succefully deleted!';
        $response['data'] = [];

        return $response;
    }
}
