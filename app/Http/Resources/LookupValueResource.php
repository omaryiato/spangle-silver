<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LookupValueResource extends JsonResource
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
            'type_id' => $this->type_id,
            'type_en_name' => $this->type?->type_en_name,
            'type_ar_name' => $this->type?->type_ar_name,
            'code' => $this->code,
            'en_meaning' => $this->en_meaning,
            'ar_meaning' => $this->ar_meaning,
            'description' => $this->description ?? null,
            'status' => $this->status,
            'created_by' => $this->created_by,

            'updated_by' => $this->updated_by,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
