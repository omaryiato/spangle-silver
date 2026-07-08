<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteImageResource extends JsonResource
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
            'theme_name' => $this->theme_name,
            'color_scheme' => $this->color_scheme,
            'font_style' => $this->font_style ?? null,
            'background_image' => $this->background_image ?? null,
            'borders' => $this->borders,
            'status' => $this->status,
            'created_at' => $this->created_at ?? null,
            'created_by' => $this->created_by ?? null,
            'updated_at' => $this->updated_at ?? null,
            'updated_by' => $this->updated_by ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
