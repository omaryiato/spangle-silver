<?php

namespace App\Http\Requests\Dashboard\LookupValue;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateLookupValue extends BaseRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('lookupValue');

        return [
            'type_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:lookup_types,id',
            ],

            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('lookup_values')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('type_id', $this->input('type_id', $id?->type_id))),
            ],

            'en_meaning' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'ar_meaning' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'color' => [
                'sometimes',
                'required',
                'required_if:type_id,1',
                'string',
                'max:100',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],

            'status' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1',
            ],
        ];
    }
}
