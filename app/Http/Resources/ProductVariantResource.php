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
            'created_by' => $this->created_by,

            'updated_by' => $this->updated_by,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),

            'color_id' => $this->color_id,
            'color_en_name' => $this->color?->en_meaning,
            'color_ar_name' => $this->color?->ar_meaning,

            'size_id' => $this->size_id,
            'size_en_name' => $this->size?->en_meaning,
            'size_ar_name' => $this->size?->ar_meaning,
        ];
    }
}
