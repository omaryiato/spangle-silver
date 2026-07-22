<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Base\BaseRequest;
use App\Models\ProductVariant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;


class AddToCart extends BaseRequest
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
                'bail',
                'required',
                'integer',
                'exists:users,id'
            ],

            'variant_id' => [
                'bail',
                'required',
                'integer',
                'exists:product_variants,id'
            ],

            'quantity' => [
                'bail',
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {

                    $variant = ProductVariant::find($this->variant_id);

                    if (!$variant) {
                        return;
                    }

                    if ($value > $variant->stock) {
                        $fail(trans('validation.exceeded_quantity', [], 'en'));
                    }
                },
            ],
        ];
    }
}
