<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ControlPanelRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'role_id' => $this->sl_no,
            'role' => $this->role,
            'role_en_name' => $this->role_en_name,
            'role_ar_name' => $this->role_ar_name,
            'role_code' => $this->role_code,
            'employee_number' => isset($this->employees)
                ? json_decode($this->employees, true)['employees'] ?? []
                : [],
            'role_status' => $this->status ?? null,
            'created_by' => $this->created_by ?? null,
            'last_updated_by' => $this->last_updated_by ?? null,
            'creation_date' => $this->creation_date ?? null,
            'last_update_date' => $this->last_update_date ?? null,
            'last_update_login' => $this->last_update_login ?? null,
            'subroles' => $this->subroles ?? null,
            'start_date' => $this->start_date ?? null,
            'end_date' => $this->end_date ?? null,
            'process_role' => $this->process_role ?? null,
        ];
    }
}
