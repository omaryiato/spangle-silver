<?php

declare(strict_types=1);

namespace App\Http\Requests\Base;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** Translate the existing failures without rerunning validation or database queries. */
    protected function failedValidation(Validator $validator): void
    {
        $details = [];
        $failed = $validator->failed();
        foreach ($validator->errors()->keys() as $field) {
            $rules = $failed[$field] ?? ['Invalid' => []];
            foreach (['ar', 'en'] as $locale) {
                $messages = [];
                foreach ($rules as $rule => $parameters) {
                    $rule = Str::snake($rule);
                    $customKey = 'validation.'.Str::afterLast($field, '.').'.'.$rule;
                    $key = app('translator')->hasForLocale($customKey, $locale) ? $customKey : 'api_validation.'.$rule;
                    if (! app('translator')->hasForLocale($key, $locale)) {
                        $key = 'api_validation.invalid';
                    }
                    $messages[] = trans($key, ['attribute' => $field, 'limit' => $parameters[0] ?? '', 'other' => implode(', ', $parameters)], $locale);
                }
                $details[$field][$locale] = implode(' ', $messages);
            }
        }
        throw new HttpResponseException(ResponseHelper::error(
            ['ar' => 'يرجى مراجعة البيانات المدخلة', 'en' => 'Please review the submitted data'], $details, 422, 'VALIDATION_ERROR'));
    }
}
