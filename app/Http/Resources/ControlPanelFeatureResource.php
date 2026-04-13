<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ControlPanelFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_id' => $this->service_id ?? null,
            'service_en_name' => $this->service_en_name ?? null,
            'service_ar_name' => $this->service_ar_name ?? null,

            // 'page_id' => $this->page_id ?? null,
            // 'page_en_name' => $this->page_en_name ?? null,
            // 'page_ar_name' => $this->page_ar_name ?? null,

            'feature_id' => $this->feature_id ?? null,
            'feature_en_name' => $this->feature_en_name ?? null,
            'feature_ar_name' => $this->feature_ar_name ?? null,
            'feature_code' => $this->feature_code ?? null,
            'feature_type' => $this->feature_type ?? null,
            'feature_status' => $this->feature_status ?? null,
            'feature_is_default' => $this->feature_is_default ?? null,
            'feature_parent_id' => $this->feature_parent_id ?? null,
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,

            // employees ARRAY
            'assigned_employees' => collect($this->employees ?? [])
            ->map(fn($employee) => [
                'employee_number' => $employee->employee_number ?? null,
                'employee_allow' => $employee->allow ?? null,
                'employee_assigned_from_date' => $employee->assigned_from_date ?? null,
                'employee_assigned_to_date' => $employee->assigned_to_date ?? null,
            ])
            ->values(),

            //  roles ARRAY
            'assigned_roles' => collect($this->roles ?? [])
                ->map(fn($role) => [
                    'role_id' => $role->role_id ?? null,
                    'role_en_name' => $role->role_en_name ?? null,
                    'role_ar_name' => $role->role_ar_name ?? null,
                    'role_allow' => $role->allow ?? null,
                    'role_assigned_from_date' => $role->assigned_from_date ?? null,
                    'role_assigned_to_date' => $role->assigned_to_date ?? null,
                ])
                ->values(),

            // 'service_id' => $this['service_id'] ?? null,
            // 'service_en_name' => $this['service_en_name'] ?? null,
            // 'service_ar_name' => $this['service_ar_name'] ?? null,

            // 'page_id' => $this['page_id'] ?? null,
            // 'page_en_name' => $this['page_en_name'] ?? null,
            // 'page_ar_name' => $this['page_ar_name']?? null,

            // 'feature_id' => $this['feature_id'],
            // 'feature_en_name' => $this['feature_en_name'],
            // 'feature_ar_name' => $this['feature_ar_name'],
            // 'feature_code' => $this['feature_code'],
            // 'feature_type' => $this['feature_type'],
            // 'feature_status' => $this['feature_status'],
            // 'feature_is_default' => $this['feature_is_default'],
            // 'created_by' => $this['created_by'],
            // 'updated_by' => $this['updated_by'],
            // 'created_at' => $this['created_at'],
            // 'updated_at' => $this['updated_at'],

        ];
    }
}

