<?php

namespace App\Http\Resources\Scholar\Info;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray($request)
    {
      
        return [
            'id' => $this->id,
            'scholar_id' => $this->scholar_id,
            'name' => ucwords(strtolower($this->school->name)),
            'class' => $this->school->class->name,
            'avatar' => $this->school->avatar,
            'shortcut' => $this->shortcut,
            'campus' => ($this->campus == 'MAIN') ?  ucwords(strtolower($this->campus)) : ucwords(strtolower($this->campus)),
            'address' => ucwords($this->address)
        ];
    }
}
