<?php

namespace App\Http\Resources\Dashboard;

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
            'lookup_value_id' => $this->id,
            'lookup_value_code' => $this->code,
            'lookup_value_meaning' => $this->meaning,
            'lookup_value_description' => $this->type_description ?? null,

            'lookup_value_id' => $this->id,
            'lookup_value_code' => $this->code,
            'lookup_value_meaning' => $this->meaning,
            'lookup_value_description' => $this->type_description ?? null,
            'lookup_value_status' => $this->status,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
