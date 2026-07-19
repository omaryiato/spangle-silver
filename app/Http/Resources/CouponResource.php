<?php

namespace App\Http\Resources;

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
            'id' => $this->id ?? null,
            'code' => $this->code ?? null,
            'discount_amount' => $this->discount_amount ?? null,
            'minimum_order_amount' => $this->minimum_order_amount ?? null,
            'max_usage' => $this->max_usage ?? null,
            'used_count' => $this->used_count ?? null,
            'expires_at' => $this->expires_at?->format('Y-m-d H:i:s') ?? null,
            'status' => $this->status ?? null,

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
