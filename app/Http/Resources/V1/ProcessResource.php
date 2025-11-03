<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'processCode' => $this->proc_cd,
            'name' => $this->proc_nm,
            'standardTime' => $this->std_time,
            'department' => new DepartmentResource($this->whenLoaded('department')),
        ];
    }
}
