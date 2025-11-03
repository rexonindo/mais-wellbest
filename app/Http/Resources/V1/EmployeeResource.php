<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'employeeId' => $this->emp_id,
            'name' => $this->emp_nm,
            'position' => $this->psition,
            'status' => $this->stats,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'shift' => new ShiftResource($this->whenLoaded('shift')),
        ];
    }
}
