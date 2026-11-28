<?php

declare(strict_types=1);

namespace App\Http\Resources\StoreSettings;

use App\Http\Resources\UserResource;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreSettingsResource extends JsonResource
{
    public function __construct(StoreSetting $resource, private bool $includeAudit = true)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'logo_url' => $this->logo_path ? asset($this->logo_path) : null,
            'favicon_url' => $this->favicon_path ? asset($this->favicon_path) : null,
            'email_address' => $this->email_address,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'currency_code' => $this->currency_code,
            'social_links' => (object) ($this->social_links ?? []),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            // $this->mergeWhen($this->includeAudit, [
            //     'created_by' => $this->created_by,
            //     'updated_by' => $this->updated_by,
            //     // 'created_at' => $this->created_at?->toIso8601String(),
            //     // 'updated_at' => $this->updated_at?->toIso8601String(),
            //     // 'created_by_user' => UserResource::make($this->whenLoaded('createdBy')),
            //     // 'updated_by_user' => UserResource::make($this->whenLoaded('updatedBy')),
            // ]),
        ];
    }
}
