<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'shiftCode' => $this->shift_cd,
            'name' => $this->shift_nm,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
        ];
    }
}
