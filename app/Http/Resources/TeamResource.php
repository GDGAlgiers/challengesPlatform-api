<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\User\ParticipantResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;


class TeamResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'participants' => ParticipantResource::collection(User::where('team_id', $this->id)->get()->toArray()),
        ];
    }

}