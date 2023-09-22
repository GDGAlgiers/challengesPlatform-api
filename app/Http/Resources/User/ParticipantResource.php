<?php

namespace App\Http\Resources\User;

use App\Http\Resources\SubmissionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this->full_name,
            'points' => $this->points,
            'role' => 'participant',
            'email_verified' => $this->email_verified_at ? true: false,
            'track' => $this->track?->type,
            'submissions' => SubmissionResource::collection($this->submissions),
        ];
    }
}
