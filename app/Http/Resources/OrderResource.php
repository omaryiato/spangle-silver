<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'user_name' => $this->user?->user_name,

            'address_id' => $this->address_id,
            'address' => $this->address?->full_name,

            'shipping_id' => $this->shipping_id ?? null,
            'method_en_name' => $this->shipping?->method_en_name ?? null,
            'method_ar_name' => $this->shipping?->method_ar_name ?? null,
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'discount' => $this->discount,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'notes' => $this->notes,
            'snap_user_name' => $this->snap_user_name,
            'snap_address' => $this->snap_address,
            'snap_city' => $this->snap_city,
            'snap_country' => $this->snap_country,
            'snap_phone' => $this->snap_phone,
            'snap_email' => $this->snap_email,
            'snap_postal_code' => $this->snap_postal_code,
            'created_by' => $this->created_by,

            'updated_by' => $this->updated_by,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'order_details' => OrderDetailResource::collection($this->whenLoaded('orderDetail')),
        ];
    }
}
