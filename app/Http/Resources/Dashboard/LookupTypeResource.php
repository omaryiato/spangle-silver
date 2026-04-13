<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LookupTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'lookup_type_id' => $this->id ?? null,
            'lookup_type_en_name' => $this->type_en_name,
            'lookup_type_ar_name' => $this->type_ar_name,
            'lookup_type_description' => $this->type_description ?? null,
            'lookup_type_status' => $this->type_status,
            'lookup_values' => $this->lookup_values,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
