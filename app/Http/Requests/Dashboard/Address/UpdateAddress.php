<?php

namespace App\Http\Requests\Dashboard\Address;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateAddress extends BaseRequest
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

        return [

            'user_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:users,id',
            ],

            'label' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
            ],

            'full_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'address_line' => [
                'sometimes',
                'required',
                'string',
                'max:500',
            ],

            'city' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],

            'country' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
            ],

            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
            ],

            'is_default' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1',
            ],
        ];
    }
}
