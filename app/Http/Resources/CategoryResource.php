<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
class CategoryResource extends JsonResource
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
            'category_en_name' => $this->category_en_name ?? null,
            'category_ar_name' => $this->category_ar_name ?? null,
            'category_description' => $this->category_description ?? null,
            'category_image' => asset("documents/category_image/" . $this->category_image) ?? null,
            'status' => $this->status ?? null,
            'created_by' => $this->created_by ?? null,

            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
