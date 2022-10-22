<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
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
            'name' => $this->name,
            'difficulty' => $this->difficulty,
            'description' => $this->description,
            'points' => $this->points,
            'attachment' => $this->attachment,
            'max_tries' => $this->max_tries,
            'requires_judge' => $this->requires_judge,
            'is_locked' => $this->is_locked
        ];
    }
}
