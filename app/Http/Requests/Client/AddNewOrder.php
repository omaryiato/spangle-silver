<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Base\BaseRequest;
use App\Models\ProductVariant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class AddNewOrder extends BaseRequest
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
                'nullable',
                'integer',
                // 'exists:users,id'
            ],

            'address_id' => [
                'bail',
                'nullable',
                'integer',
                // 'exists:addresses,id'
            ],

            'coupon_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:coupons,id'
            ],

            'shipping_id' => [
                'bail',
                'required',
                'integer',
                'exists:shipping_methods,id'
            ],

            'order_details' => [
                'bail',
                'required',
                'array',
                'min:1'
            ],

            'order_details.*.variant_id' => [
                'bail',
                'required',
                'integer',
                'exists:product_variants,id'
            ],

            'order_details.*.quantity' => [
                'bail',
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {

                    // استخراج index من الحقل
                    // order_details.0.quantity
                    preg_match('/order_details\.(\d+)\.quantity/', $attribute, $matches);

                    $index = $matches[1] ?? null;

                    if ($index === null) {
                        return;
                    }

                    $variantId = request("order_details.$index.variant_id");

                    $variant = ProductVariant::find($variantId);

                    if (!$variant) {
                        return;
                    }

                    if ($value > $variant->stock) {
                        $fail(trans('validation.exceeded_quantity', [], 'en'));
                    }
                },
            ],

            'order_details.*.unit_price' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],

            'order_details.*.total_price' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],

            'subtotal' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],
            'shipping_cost' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],
            'discount' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],
            'total_price' => [
                'bail',
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }
}
