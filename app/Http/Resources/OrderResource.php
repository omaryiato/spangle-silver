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
            'id' => $this->id ?? null,

            'user_id' => $this->user_id ?? null,
            'user_name' => $this->user?->user_name ?? null,

            'address_id' => $this->address_id ?? null,
            'address' => $this->address?->full_name ?? null,

            'shipping_id' => $this->shipping_id ?? null,
            'method_en_name' => $this->shipping?->method_en_name ?? null,
            'method_ar_name' => $this->shipping?->method_ar_name ?? null,
            'subtotal' => $this->subtotal ?? null,
            'shipping_cost' => $this->shipping_cost ?? null,
            'discount' => $this->discount ?? null,
            'total_price' => $this->total_price ?? null,
            'status' => $this->status ?? null,
            'notes' => $this->notes ?? null,
            'snap_user_name' => $this->snap_user_name ?? null,
            'snap_address' => $this->snap_address ?? null,
            'snap_city' => $this->snap_city ?? null,
            'snap_country' => $this->snap_country ?? null,
            'snap_phone' => $this->snap_phone ?? null,
            'snap_email' => $this->snap_email ?? null,
            'snap_postal_code' => $this->snap_postal_code ?? null,
            'created_by' => $this->created_by ?? null,

            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
            'order_details' => OrderDetailResource::collection($this->whenLoaded('details')),
        ];
    }
}
