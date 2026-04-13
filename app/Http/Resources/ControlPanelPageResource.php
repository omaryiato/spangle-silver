<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ControlPanelFeatureResource;

class ControlPanelPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_id' => $this->service_id,
            'service_en_name' => $this->service_en_name,
            'service_ar_name' => $this->service_ar_name,

            'page_id' => $this->page_id,
            'page_en_name' => $this->page_en_name,
            'page_ar_name' => $this->page_ar_name,
            'page_code' => $this->page_code,
            'page_route' => $this->page_route ?? null,
            'page_status' => $this->page_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'features' => ControlPanelFeatureResource::collection(
                $this->features ?? []
            ),

            // 'service_id' => $this['service_id'],
            // 'service_en_name' => $this['service_en_name'],
            // 'service_ar_name' => $this['service_ar_name'],

            // 'page_id' => $this['page_id'],
            // 'page_en_name' => $this['page_en_name'],
            // 'page_ar_name' => $this['page_ar_name'],
            // 'page_code' => $this['page_code'],
            // 'page_route' => $this['page_route'],
            // 'page_status' => $this['page_status'],
            // 'created_by' => $this['created_by'],
            // 'updated_by' => $this['updated_by'],
            // 'created_at' => $this['created_at'],
            // 'updated_at' => $this['updated_at'],


        ];
    }
}

