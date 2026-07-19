<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'user_full_name' => $this->user?->full_name ?? null,

            'label' => $this->label ?? null,
            'full_name' => $this->full_name ?? null,
            'address_line' => $this->address_line ?? null,
            'city' => $this->city ?? null,
            'country' => $this->country ?? null,
            'postal_code' => $this->postal_code ?? null,
            'phone' => $this->phone ?? null,
            'is_default' => $this->is_default ?? null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
