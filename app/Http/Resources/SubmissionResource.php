<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
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
            'id' => $this->id,
            'track' => $this->track->type,
            'challenge' => new ChallengeResource($this->challenge),
            'included_attachment' => $this->attachment ? true: false,
            'status' => $this->status,
            'assigned_points' => $this->assigned_points,
            'submitted_at' => $this->created_at ? $this->created_at->diffForHumans() : NULL
        ];
    }
}
