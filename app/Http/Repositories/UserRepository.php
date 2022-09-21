<?php
namespace App\Http\Repositories;

use App\Http\Resources\User\JudgeResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Class UserRepository {

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
}
