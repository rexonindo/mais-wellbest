<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itm_cd' => ['required', 'string', 'max:50', 'unique:itm_tbl,itm_cd'],
            'itm_nm' => ['required', 'string', 'max:100'],
            'itm_type' => ['nullable', 'string', 'max:50'],
            'fg_flg' => ['nullable', 'boolean'],
            'uom' => ['nullable', 'string', 'max:20'],
            'std_rate' => ['nullable', 'numeric'],
        ];
    }
}
