<?php

namespace App\Http\Requests\Dashboard\LookupType;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateLookupType extends BaseRequest
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
        $id = $this->route('lookupType');

        return [
            'type_en_name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('lookup_types', 'type_en_name')
                    ->ignore($id),
            ],

            'type_ar_name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('lookup_types', 'type_ar_name')
                    ->ignore($id),
            ],

            'type_description' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
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
