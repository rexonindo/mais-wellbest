<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $itemId = $this->route('item');

        return [
            'itm_cd' => ['required', 'string', 'max:50', Rule::unique('itm_tbl', 'itm_cd')->ignore($itemId)],
            'itm_nm' => ['required', 'string', 'max:100'],
            'itm_type' => ['nullable', 'string', 'max:50'],
            'fg_flg' => ['nullable', 'boolean'],
            'uom' => ['nullable', 'string', 'max:20'],
            'std_rate' => ['nullable', 'numeric'],
        ];
    }
}
