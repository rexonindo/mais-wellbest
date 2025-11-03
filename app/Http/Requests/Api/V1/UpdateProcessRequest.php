<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $processId = $this->route('process');

        return [
            'proc_cd' => ['required', 'string', 'max:50', Rule::unique('proc_tbl', 'proc_cd')->ignore($processId)],
            'proc_nm' => ['required', 'string', 'max:100'],
            'dept_cd' => ['nullable', 'string', 'exists:dept_tbl,dept_cd'],
            'std_time' => ['nullable', 'numeric'],
        ];
    }
}
