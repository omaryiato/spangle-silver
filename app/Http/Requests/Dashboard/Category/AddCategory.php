<?php

namespace App\Http\Requests\Dashboard\Category;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;


class AddCategory extends BaseRequest
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
            'category_en_name' => [
                'required',
                'string',
                'max:255',
                'unique:category,category_en_name',
            ],

            'category_ar_name' => [
                'required',
                'string',
                'max:255',
                'unique:category,category_ar_name',
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
