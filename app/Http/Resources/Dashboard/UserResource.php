<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Dashboard\AddressResource;

class UserResource extends JsonResource
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
            'full_name' => $this->full_name,
            'user_name' => $this->user_name,
            'phone_number' => $this->phone_number ?? null,
            'password' => $this->password,
            'user_status' => $this->status,
            'user_type' => $this->user_type,
            'user_addresses' => AddressResource::collection($this->whenLoad('addresses')),
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
