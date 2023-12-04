<?php

namespace App\Http\Repositories;

use App\Http\Resources\Team\TeamResource;
use App\Models\Team;
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
        $validator = Validator::make($request->all(), ['name' => 'required|unique:teams,name']);

        if ($validator->fails()) {
            $response['success'] = false;
            $response['message'] = 'Validation failed!';
            $response['data'] = $validator->errors();
            return $response;
        }
        $team = Team::create($request);
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
}