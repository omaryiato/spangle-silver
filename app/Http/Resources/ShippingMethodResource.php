<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
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
            'method_en_name' => $this->method_en_name ?? null,
            'method_ar_name' => $this->method_ar_name ?? null,
            'method_price' => $this->price ?? null,
            'method_estimated_days' => $this->estimated_days ?? null,
            'status' => $this->status ?? null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
