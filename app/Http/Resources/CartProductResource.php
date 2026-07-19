<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
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

            'variant_id' => $this->variant_id ?? null,

            'color' => $this->color_id ?? null,
            'color_en_name' => $this->variant?->color->en_meaning ?? null,
            'color_ar_name' => $this->variant?->color->ar_meaning ?? null,

            'size' => $this->size_id ?? null,
            'size_en_name' => $this->variant?->size->en_meaning ?? null,
            'size_ar_name' => $this->variant?->size->ar_meaning ?? null,

            'variant_product' => $this->product_id ?? null,
            'variant_product_name' => $this->variant?->product->product_en_name ?? null,

            'quantity' => $this->quantity ?? null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
