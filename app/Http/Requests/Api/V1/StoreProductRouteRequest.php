<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itm_type' => ['required', 'string', 'max:50'],
            'seq_no' => [
                'required',
                'integer',
                Rule::unique('prdroute_tbl')->where(function ($query) {
                    return $query->where('itm_type', $this->itm_type);
                })
            ],
            'proc_cd' => ['required', 'string', 'exists:proc_tbl,proc_cd'],
        ];
    }
}
