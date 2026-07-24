<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Trainer */
class TrainerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'initials' => $this->initials,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'dob' => optional($this->dob)->format('Y-m-d'),
            'joining_date' => optional($this->joining_date)->format('Y-m-d'),
            'specialization' => $this->specialization,
            'experience' => $this->experience,
            'certifications' => $this->certifications,
            'skills' => $this->skills,
            'status' => $this->status,
            'profile_image' => $this->profile_image,
            'background_image' => $this->background_image,
            'profile_image_url' => $this->profile_image_url,
            'background_image_url' => $this->background_image_url,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'created_at_human' => optional($this->created_at)->diffForHumans(),
        ];
    }
}
