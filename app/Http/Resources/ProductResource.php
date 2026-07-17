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
            'id' => $this->id ?? null,
            'produst_en_name' => $this->product_en_name ?? null,
            'produst_ar_name' => $this->product_ar_name ?? null,
            'produst_en_description' => $this->produst_en_description ?? null,
            'produst_ar_description' => $this->produst_ar_description ?? null,
            'status' => $this->product_status ?? null,
            'product_reels' => $this->product_reels ?? null,
            'created_by' => $this->created_by ?? null,

            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,

            'category_id' => $this->category_id ?? null,
            'category_en_name' => $this->category?->category_en_name ?? null,
            'category_ar_name' => $this->category?->category_ar_name ?? null,

            'product_material' => $this->product_material ?? null,
            'material_en_name' => $this->material?->en_meaning ?? null,
            'material_ar_name' => $this->material?->ar_meaning ?? null,

            'product_stone' => $this->product_stone ?? null,
            'stone_en_name' => $this->stone?->en_meaning ?? null,
            'stone_ar_name' => $this->stone?->ar_meaning ?? null,

            'produst_images' => ProductImageResource::collection($this->whenLoaded('images')),
            'product_variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'product_reviews' => ProductReviewResource::collection($this->whenLoaded('reviews')),

            'stream_url' =>preg_replace('#(?<!:)//\+#', '/', route('reel.stream', $this->id)) ?? null,
        ];
    }
}
