<?php

namespace App\Http\Requests\Dashboard\SiteTheme;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateTheme extends BaseRequest
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

            'theme_name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],

            'color_scheme' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'font_style' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],

            'background_image' => [
                'sometimes',
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'borders' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
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
