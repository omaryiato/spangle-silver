<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coupon_id' => $this->id ?? null,
            'coupon_code' => $this->code,
            'coupon_discount_amount' => $this->discount_amount,
            'coupon_minimum_order_amount' => $this->minimum_order_amount ?? null,
            'coupon_max_usage' => $this->max_usage ?? null,
            'coupon_used_count' => $this->used_count ?? null,
            'coupon_expires_at' => $this->expires_at ?? null,
            'coupon_status' => $this->status,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
