<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWishListResource extends JsonResource
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
            'full_name' => $this->user?->full_name ?? null,

            'product_id' => $this->product_id ?? null,
            'product_en_name' => $this->product?->product_en_name ?? null,
            'product_ar_name' => $this->product?->product_ar_name ?? null,
            
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
