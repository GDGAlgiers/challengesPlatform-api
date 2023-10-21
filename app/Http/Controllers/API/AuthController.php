<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User\AdministratorResource;
use App\Http\Resources\User\JudgeResource;
use App\Http\Resources\User\ParticipantResource;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|unique:users,full_name',
            'email' => 'required|email|unique:users,email',
            'track' => 'required|string|exists:tracks,type',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed",$validator->errors());
        }

        $usersCount = count(User::where('ip', $request->ip())->get());
        if($usersCount >=2) {
            return $this->sendError("You have reached your accounts limit, contact admins in case of issues");
        }

        $trackID = Track::where('type', $request->track)->pluck('id')->first();

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'track_id' => $trackID,
            'password' => Hash::make($request->password),
            'role' => 'participant',
            'points' => 0,
            'ip' => $request->ip()
        ]);

        event(new Registered($user));
        $token = $user->createToken('arizona-platform')->plainTextToken;
        $result = [
            'user' => new ParticipantResource($user),
            'token' => $token
        ];

        return $this->sendResponse($result,'Registration was made succesfully!');

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|exists:users,full_name',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed", $validator->errors());
        }

        $user = User::where('full_name',$request->full_name)->first();
        if(!$user) {
            return $this->sendError("No user found with these credentials");
        }
        if(Auth::attempt($validator->validated())){
            $token = $user->createToken('arizona-platform')->plainTextToken;
            $result = [
                'user' => $user->role === 'participant' ? new ParticipantResource($user) : ($user->role === 'judge' ? new JudgeResource($user) : new AdministratorResource($user)),
                'token' => $token
            ];
            return $this->sendResponse($result,'Successfull login');
        }
        else{
            return $this->sendError("Incorrect data", ["password" => ["No user found with the specified data"]]);
        }
    }

    public function logout(){
        $id= auth('sanctum')->id();
        $user = User::find($id);
        $user->tokens()->delete();

        return $this->sendResponse([],'Logged out succesfully');
    }
}
