<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductImageResource;
use App\Http\Resources\ProductVariantResource;

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
            'id' => $this->id,
            'produst_en_name' => $this->product_en_name,
            'produst_ar_name' => $this->product_ar_name,
            'produst_en_description' => $this->produst_en_description,
            'produst_ar_description' => $this->produst_ar_description,
            'status' => $this->product_status,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,

            'category_id' => $this->category_id,
            'category_en_name' => $this->category?->category_en_name ?? null,
            'category_ar_name' => $this->category?->category_ar_name ?? null,

            'product_material' => $this->product_material,
            'material_en_name' => $this->material?->en_meaning,
            'material_ar_name' => $this->material?->ar_meaning,

            'product_stone' => $this->product_stone,
            'stone_en_name' => $this->stone?->en_meaning,
            'stone_ar_name' => $this->stone?->ar_meaning,

            'produst_images' => ProductImageResource::collection($this->whenLoaded('images')),
            'product_variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'product_reviews' => ProductReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
