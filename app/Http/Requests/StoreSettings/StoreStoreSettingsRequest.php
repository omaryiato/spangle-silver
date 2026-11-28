<?php

declare(strict_types=1);

namespace App\Http\Requests\StoreSettings;

use App\Http\Requests\Base\BaseRequest;

class StoreStoreSettingsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name_ar' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/u'],
            'name_en' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/u'],
            'currency_code' => ['required', 'string', 'size:3', 'regex:/^[A-Z]{3}$/D'],
            'description_ar' => ['sometimes', 'nullable', 'string'],
            'description_en' => ['sometimes', 'nullable', 'string'],
            'email_address' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:30'],
            'whatsapp_number' => ['sometimes', 'nullable', 'string', 'max:30'],
            'logo' => ['sometimes', 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'favicon' => ['sometimes', 'required', 'image', 'mimes:png', 'max:512', 'dimensions:max_width=512,max_height=512'],
            'social_links' => ['sometimes', 'array', 'max:20', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! is_array($value)) {
                    return;
                }
                foreach (array_keys($value) as $key) {
                    if (! is_string($key) || ! preg_match('/^[a-z][a-z0-9_]{0,39}$/D', $key)) {
                        $fail('api_validation.invalid')->translate(['attribute' => $attribute]);

                        return;
                    }
                }
            }],
            'social_links.*' => ['nullable', 'url:http,https', 'max:2048'],
            'logo_path' => ['prohibited'],
            'favicon_path' => ['prohibited'],
            'created_by' => ['prohibited'],
            'updated_by' => ['prohibited'],
        ];
    }
}
