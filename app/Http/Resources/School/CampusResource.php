<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;

class CampusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'oldname' => $this->oldname,
            'campus' => $this->campus,
            'address' => $this->address,
            'is_main' => $this->is_main,
            'is_active' => $this->is_active,
            'grading' => $this->grading,
            'term' => $this->term,
            'region' => $this->region,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'assigned' => $this->assigned,
            'courses' => CourseResource::collection($this->courses)
        ];
    }
}
