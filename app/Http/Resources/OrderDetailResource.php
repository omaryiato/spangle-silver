<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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

            'order_id' => $this->order_id ?? null,

            'variant_id' => $this->variant_id ?? null,
            'variant' => ProductVariantResource::make($this->whenLoaded('variant')),
            'sku' => $this->whenLoaded('variant', fn () => $this->variant?->sku ?? null),

            'color' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('color') ? $this->variant?->color?->id ?? null : null),
            'color_en_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('color') ? $this->variant?->color?->en_meaning ?? null : null),
            'color_ar_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('color') ? $this->variant?->color?->ar_meaning ?? null : null),

            'size' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('size') ? $this->variant?->size?->id ?? null : null),
            'size_en_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('size') ? $this->variant?->size?->en_meaning ?? null : null),
            'size_ar_name' => $this->whenLoaded('variant', fn () => $this->variant?->relationLoaded('size') ? $this->variant?->size?->ar_meaning ?? null : null),

            'quantity' => $this->quantity ?? null,
            'unit_price' => $this->unit_price ?? null,
            'total_price' => $this->total_price ?? null,

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
