<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming any authenticated user can update a department
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department'); // Get the department ID from the route

        return [
            'dept_cd' => ['required', 'string', 'max:20', Rule::unique('dept_tbl', 'dept_cd')->ignore($departmentId)],
            'dept_nm' => ['required', 'string', 'max:100'],
            'descrp' => ['nullable', 'string'],
        ];
    }
}
