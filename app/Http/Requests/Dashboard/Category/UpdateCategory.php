<?php

namespace App\Http\Requests\Dashboard\Category;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;


class UpdateCategory extends BaseRequest
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
        $id = $this->route("category_id");

        return [
            'category_en_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category', 'category_en_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'category_ar_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category', 'category_ar_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'category_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'category_image' => [
                'nullable',
                'string',
                'max:500',
            ],

            'status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],
        ];
    }
}
