<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'produst_id' => $this->id,
            'category_id' => $this->category_id,
            'produst_en_name' => $this->product_en_name,
            'produst_ar_name' => $this->product_ar_name,
            'produst_en_description' => $this->produst_en_description,
            'produst_ar_description' => $this->produst_ar_description,

            'product_material' => $this->product_material,
            'material_name' => $this->material_name,

            'product_stone' => $this->product_stone,
            'stone_name' => $this->stone_name,

            'produst_images' => $this->produst_images ,

            'product_variants' => $this->product_variants,

            'status' => $this->product_status,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
