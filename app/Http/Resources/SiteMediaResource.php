<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteMediaResource extends JsonResource
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

            'file_name' => $this->file_name,

            'original_name' => $this->original_name,

            'file_path' => asset(str_replace('//', '/', 'api/'.$this->file_path)),

            'file_type' => $this->file_type,

            'file_size' => $this->file_size,

            'mime_type' => $this->mime_type,

            'alt_text' => $this->alt_text,

            'type' => $this->type,
            'status' => $this->status,

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,

        ];
    }
}
