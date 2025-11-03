<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'itemCode' => $this->itm_cd,
            'name' => $this->itm_nm,
            'itemType' => $this->itm_type,
            'isFinishedGood' => $this->fg_flg,
            'uom' => $this->uom,
            'standardRate' => $this->std_rate,
        ];
    }
}
