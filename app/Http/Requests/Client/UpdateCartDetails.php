<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Base\BaseRequest;
use App\Models\ProductVariant;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateCartDetails extends BaseRequest
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
            'quantity' => [
                'bail',
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {

                    $variant = ProductVariant::find(request('variant_id'));

                    if (!$variant) {
                        return;
                    }

                    if ($value > $variant->stock) {
                        $fail(trans('validation.exceeded_quantity'));
                    }
                },
            ],
        ];
    }
}
