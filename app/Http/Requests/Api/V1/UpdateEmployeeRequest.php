<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
        $employeeId = $this->route('employee');

        return [
            'emp_id' => ['required', 'string', 'max:20', Rule::unique('empl_tbl', 'emp_id')->ignore($employeeId)],
            'emp_nm' => ['required', 'string', 'max:100'],
            'psition' => ['nullable', 'string', 'max:100'],
            'dept_cd' => ['nullable', 'string', 'exists:dept_tbl,dept_cd'],
            'shift_cd' => ['nullable', 'string', 'exists:shift_tbl,shift_cd'],
            'stats' => ['required', 'string', Rule::in(['Active', 'Inactive'])],
        ];
    }
}
