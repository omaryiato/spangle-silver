<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllActiveEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'cost_center_number' => $this->cost_center
                ?? $this->flex_value
                ?? null,

            'cost_center_name' => $this->name
                ?? $this->description ?? $this->cost_center_name
                ?? null,

            'employee_number' => $this->employee_number ?? null,
            'employee_name' => $this->full_name ?? null,
            'employee_arabic_name' => $this->emp_arabic_name ?? $this->full_name ?? null,
            'job_arabic_name' => $this->job_name ?? null,
            'job_name' => $this->job_name_arb ?? $this->job_name ?? null,
        ];
    }
}
