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
            'variant' => ProductVariantResource::make($this->whenLoaded('variant')),

            'id' => $this->id ?? null,

            'user_id' => $this->user_id ?? null,
            'full_name' => $this->whenLoaded('user', fn () => $this->user?->full_name ?? null),

            'variant_id' => $this->variant_id ?? null,

            'color' => $this->whenLoaded('variant', fn () => $this->variant?->color_id),
            'color_en_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('color') ? $this->variant?->color?->en_meaning ?? null : null),
            'color_ar_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('color') ? $this->variant?->color?->ar_meaning ?? null : null),

            'size' => $this->whenLoaded('variant', fn () => $this->variant?->size_id),
            'size_en_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('size') ? $this->variant?->size?->en_meaning ?? null : null),
            'size_ar_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('size') ? $this->variant?->size?->ar_meaning ?? null : null),

            'variant_product' => $this->whenLoaded('variant', fn () => $this->variant?->product_id),
            'variant_product_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('product') ? $this->variant?->product?->product_en_name ?? null : null),

            'quantity' => $this->quantity ?? null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
