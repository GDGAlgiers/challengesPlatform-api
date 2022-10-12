<?php
namespace App\Http\Repositories;

use App\Http\Resources\User\JudgeResource;
use App\Http\Resources\User\ParticipantResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Class UserRepository {

    public function create_participant($request) {
        $response = [];
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|unique:users,full_name',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'track_id' => 'required|exists:tracks,id'
        ]);

        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed!';
            $response['data'] = $validator->errors();
            return $response;
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'track_id' => $request->track_id,
            'email' => $request->email,
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
            'full_name' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation errors!';
            $response['data'] = $validator->errors();
            return $response;
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'judge'
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
