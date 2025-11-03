<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
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
            'workOrderNumber' => $this->wo_no,
            'purchaseOrderNumber' => $this->po_no,
            'requestDate' => $this->req_dt,
            'plannedQuantity' => $this->plan_qty,
            'startDate' => $this->start_dt,
            'endDate' => $this->end_dt,
            'status' => $this->stats,
            'item' => new ItemResource($this->whenLoaded('item')),
        ];
    }
}
