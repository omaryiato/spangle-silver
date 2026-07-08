<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'sku' => $this->sku,
            'stock' => $this->stock,
            'price' => $this->price,
            'status' => $this->status,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,

            'color_id' => $this->color_id,
            'color_en_name' => $this->color?->en_meaning,
            'color_ar_name' => $this->color?->ar_meaning,

            'size_id' => $this->size_id,
            'size_en_name' => $this->size?->en_meaning,
            'size_ar_name' => $this->size?->ar_meaning,
        ];
    }
}
