<?php

namespace App\Http\Resources;

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
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'material' => LookupValueResource::make($this->whenLoaded('material')),
            'stone' => LookupValueResource::make($this->whenLoaded('stone')),
            'product_material' => $this->product_material,
            'product_stone' => $this->product_stone,

            'id' => $this->id ?? null,
            'produst_en_name' => $this->product_en_name ?? null,
            'produst_ar_name' => $this->product_ar_name ?? null,
            'product_en_description' => $this->product_en_description ?? null,
            'product_ar_description' => $this->product_ar_description ?? null,
            'product_price' => $this->product_price ?? null,
            'status' => $this->product_status ?? null,
            'product_reels' => $this->product_reels ? asset($this->product_reels) : null,

            'category_id' => $this->category_id ?? null,
            'category_en_name' => $this->whenLoaded('category', fn () => $this->category?->category_en_name ?? null),
            'category_ar_name' => $this->whenLoaded('category', fn () => $this->category?->category_ar_name ?? null),

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,

            'produst_images' => ProductImageResource::collection($this->whenLoaded('images')),
            'product_variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'product_reviews' => ProductReviewResource::collection($this->whenLoaded('reviews')),

            'stream_url' => preg_replace('#(?<!:)//\+#', '/', route('reel.stream', $this->id)) ?? null,
        ];
    }
}
