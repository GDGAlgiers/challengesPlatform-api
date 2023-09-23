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
            'track' => $this->track->type,
            'name' => $this->name,
            'author' => $this->author,
            'difficulty' => $this->difficulty,
            'description' => $this->description,
            'points' => $this->points,
            'attachment' => $this->attachment,
            'external_resource' => $this->external_resource,
            'max_tries' => $this->max_tries,
            'requires_judge' => $this->requires_judge ? true: false,
            'is_locked' => $this->is_locked ? true: false
        ];
    }
}
