<?php

namespace App\Http\Requests\Dashboard\Coupon;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCoupon extends BaseRequest
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
        $id = $this->route('coupon');

        return [

            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')
                    ->ignore($id),
            ],

            'discount_amount' => [
                'sometimes',
                'required',
                'numeric',
                'gt:0',
            ],

            'minimum_order_amount' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],

            'max_usage' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],

            'used_count' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],

            'expires_at' => [
                'sometimes',
                'nullable',
                'date',
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
