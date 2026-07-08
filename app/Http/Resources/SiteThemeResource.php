<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteThemeResource extends JsonResource
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
            'created_by' => $this->created_by,

            'updated_by' => $this->updated_by,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
