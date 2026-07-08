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
            'id' => $this->id,
            'category_en_name' => $this->category_en_name,
            'category_ar_name' => $this->category_ar_name,
            'category_description' => $this->category_description,
            'category_image' => asset("documents/category_image/" . $this->category_image) ?? null,
            'status' => $this->status,
            'created_at' => $this->created_at ?? null,
            'created_by' => $this->updated_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'updated_by' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
