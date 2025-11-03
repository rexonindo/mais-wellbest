<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $workOrderId = $this->route('work_order');

        return [
            'wo_no' => ['required', 'string', 'max:50', Rule::unique('wo_tbl', 'wo_no')->ignore($workOrderId)],
            'itm_cd' => ['required', 'string', 'max:50', 'exists:itm_tbl,itm_cd'],
            'po_no' => ['nullable', 'string', 'max:50'],
            'req_dt' => ['nullable', 'date'],
            'plan_qty' => ['nullable', 'numeric'],
            'start_dt' => ['nullable', 'date'],
            'end_dt' => ['nullable', 'date'],
            'stats' => ['required', 'string', Rule::in(['Planned', 'In Progress', 'Completed', 'Cancelled'])],
        ];
    }
}
