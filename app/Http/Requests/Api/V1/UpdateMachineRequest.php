<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMachineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $machineId = $this->route('machine');

        return [
            'mchn_cd' => ['required', 'string', 'max:50', Rule::unique('mchn_tbl', 'mchn_cd')->ignore($machineId)],
            'mchn_nm' => ['nullable', 'string', 'max:100'],
            'dept_cd' => ['nullable', 'string', 'exists:dept_tbl,dept_cd'],
            'uom' => ['nullable', 'string', 'max:20'],
            'dsc' => ['nullable', 'string', 'max:50'],
            'stats' => ['required', 'string', Rule::in(['Running', 'Idle', 'Maintenance', 'Breakdown'])],
        ];
    }
}
