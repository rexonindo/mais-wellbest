<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
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
            'machineCode' => $this->mchn_cd,
            'name' => $this->mchn_nm,
            'uom' => $this->uom,
            'description' => $this->dsc,
            'status' => $this->stats,
            'department' => new DepartmentResource($this->whenLoaded('department')),
        ];
    }
}
