<?php

namespace App\Http\Repositories;

use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class TeamRepository
{
    public function getTeams()
    {
        $response = [];
        $teams = Team::all();
        $response['success'] = true;
        $response['data'] = TeamResource::collection($teams);
        $response['message'] = 'Successfully retrieved all the users!';
        return $response;

    }
    public function getTeam($id)
    {
        $response = [];
        $team = Team::find($id);
        $response['success'] = true;
        $response['data'] = new TeamResource($team);
        $response['message'] = 'Successfully retrieved the team!';
        return $response;
    }

    public function createTeam($request)
    {
        $response = [];
        $validator = Validator::make($request->all(),
         ['name' => 'required|unique:teams,name',
         'token' => 'required|unique|min:8:teams,token',
        ]);

        if ($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed!';
            $response['data'] = $validator->errors();
            return $response;
        }
        $team = Team::create([
            'name' => $request->name,
            'token'=> $request->token,
        ]);
        $response['success'] = true;
        $response['data'] = $team;
        $response['message'] = 'Successfully created the team!';
        return $response;
    }

    public function updateTeam($request, $id)
    {
        $response = [];
        $team = Team::find($id);
        $team->name = $request->name;
        $team->save();
        $response['success'] = true;
        $response['data'] = $team;
        $response['message'] = 'Successfully updated the team!';
        return $response;
    }

    public function deleteTeam($id)
    {
        $response = [];
        $team = Team::find($id);
        $team->delete();
        $response['success'] = true;
        $response['data'] = $team;
        $response['message'] = 'Successfully deleted the team!';
        return $response;
    }

    public function addMember($request, $id)
    {
        $response = [];
        $member = User::find($request->id);

        $member->team_id = $id;
        $member->save();
        $response['success'] = true;
        $response['data'] = $member;
        $response['message'] = 'Successfully added the member!';
        return $response;
    }


    public function removeMember($request)
    {
        $response = [];

        $member = User::find($request->id);
        $member->team_id = null;
        $member->save();
        $response['success'] = true;
        $response['data'] = $member;
        $response['message'] = 'Successfully removed the member!';
        return $response;
    }
}