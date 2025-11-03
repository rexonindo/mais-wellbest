<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proc_cd' => ['required', 'string', 'max:50', 'unique:proc_tbl,proc_cd'],
            'proc_nm' => ['required', 'string', 'max:100'],
            'dept_cd' => ['nullable', 'string', 'exists:dept_tbl,dept_cd'],
            'std_time' => ['nullable', 'numeric'],
        ];
    }
}
