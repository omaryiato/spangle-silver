<?php

declare(strict_types=1);

namespace App\Http\Requests\Client;

use App\Http\Requests\Base\BaseRequest;

class AddNewOrder extends BaseRequest
{
    public function rules(): array
    {
        return [
            'address_id' => ['nullable', 'integer', 'exists:addresses,id'],
            'coupon_id' => ['nullable', 'integer', 'exists:coupons,id'],
            'shipping_id' => ['required', 'integer', 'exists:shipping_methods,id'],
            'order_details' => ['required', 'array', 'list', 'min:1', 'max:100'],
            'order_details.*.variant_id' => ['required', 'integer', 'distinct', 'exists:product_variants,id'],
            'order_details.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'snap_user_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'snap_address' => ['required_without:address_id', 'nullable', 'string', 'max:500'],
            'snap_city' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'snap_country' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'snap_phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'snap_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'snap_postal_code' => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }
}
