<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ControlPanelServicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_id' => $this->id ?? null,
            'service_en_name' => $this->service_en_name,
            'service_ar_name' => $this->service_ar_name,
            'service_code' => $this->service_code,
            'service_logo' => $this->service_logo ?
                            asset('documents/e_control_panel_service_logo/' . $this->service_logo): null,
            'service_domain' => $this->service_domain,
            'service_status' => $this->service_status,
            'service_session_timeout' => $this->service_session_timeout,
            'service_icon' => $this->service_icon ?? null,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];
    }
}
