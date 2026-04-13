<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'cost_center_number' => $this->cost_center ?? null,
            'cost_center_name' => $this->cost_center_name ?? null,
            'employee_number' => $this->employee_number ?? null,
            'employee_name' => $this->full_name ?? null,
            'employee_arabic_name' => $this->emp_arabic_name ?? $this->full_name ?? null,
            'nationality' => $this->nationality ?? null,
            'nationality_arabic_name' => $this->nation_arb ??  $this->nationality ?? null,
            'job_name' =>  $this->job_name ?? $this->job_name_arb ?? null,
            'job_arabic_name' =>  $this->job_name_arb ?? $this->job_name ?? null,
            'location_name' => $this->location_name ?? null,
            'manager_number' => $this->mgr_number ?? null,
            'rgnl_number' => $this->rgnl_number ?? null,
            'dpty_number' => $this->dpty_number ?? null,
            'dupty_number' => $this->dupty_number ?? null,
            'admin_number' => $this->admin ?? null,
            'admin_officer_number' => $this->admim_officer_no ?? null,
            'appointment_date' => $this->appointment_date ?? null,
            'contract_duration' => $this->contract_duration ?? null,
        ];
    }
}
