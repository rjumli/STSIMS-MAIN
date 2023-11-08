<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course' => $this->course->name,
            'certification' => $this->certification,
            'years' => $this->years,
            'validity' => $this->validity,
            'type' => $this->type,
            'school_id' => $this->school_id,
            'is_active' => $this->is_active,
        ];
    }
}
