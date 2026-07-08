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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->user?->full_name,

            'product_id' => $this->product_id,
            'product_en_name' => $this->product?->product_en_name,
            'product_ar_name' => $this->product?->product_ar_name,
            'created_at' => $this->created_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
