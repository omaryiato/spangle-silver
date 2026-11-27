<?php

namespace App\Http\Requests\Dashboard\SiteMedia;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class AddMedia extends BaseRequest
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
            'media' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4', 'max:51200'],
            'alt_text' => ['sometimes', 'nullable', 'array:ar,en'],
            'alt_text.ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'alt_text.en' => ['sometimes', 'nullable', 'string', 'max:255'],

            'type' => [
                'required',
                'string',
                'max:50',
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
