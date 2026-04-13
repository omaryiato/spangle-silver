<?php

namespace App\Http\Resources\Dashboard;

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
            'method_id' => $this->id ?? null,
            'method_en_name' => $this->method_en_name,
            'method_ar_name' => $this->method_ar_name,
            'method_price' => $this->price ?? null,
            'method_estimated_days' => $this->estimated_days ?? null,
            'status' => $this->status,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];
    }
}
