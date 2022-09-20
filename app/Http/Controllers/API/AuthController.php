<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User\AdministratorResource;
use App\Http\Resources\User\JudgeResource;
use App\Http\Resources\User\ParticipantResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed",$validator->errors());
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'participant',
            'points' => 0
        ]);
        $token = $user->createToken('devfest')->plainTextToken;
        $result = [
            'user' => new ParticipantResource($user),
            'token' => $token
        ];
        Auth::login($user);
        return $this->sendResponse($result,'Registration succesfull');

    }

    public function login(Request $request){
        $validator = $request->validate([
            'full_name' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('full_name',$request->full_name)->first();
        if(!$user) {
            return $this->sendError("No user found with these credentials");
        }
        if(Auth::attempt($validator)){
            $token = $user->createToken('devfest22')->plainTextToken;
            $result = [
                'user' => $user->role === 'participant' ? new ParticipantResource($user) : ($user->role === 'judge' ? new JudgeResource($user) : new AdministratorResource($user)),
                'token' => $token
            ];
            return $this->sendResponse($result,'Login succesfull');
        }
        else{
            return $this->sendError("No user found with the specified data");
        }
    }
public function logout(){
        $id=auth('sanctum')->id();
        $user = User::find($id);
        $user->tokens()->delete();
    return $this->sendResponse([],'Logged out succesfully');
}
}
