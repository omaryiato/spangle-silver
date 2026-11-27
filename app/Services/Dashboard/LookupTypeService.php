<?php

namespace App\Services\Dashboard;

use App\Models\LookupType;
use App\Repositories\Dashboard\LookupTypeRepository;
use App\Services\ActingUserService;

class LookupTypeService
{
    protected $lookupTypeRepository;

    public function __construct(LookupTypeRepository $lookupTypeRepository, protected ActingUserService $actingUserService)
    {
        $this->lookupTypeRepository = $lookupTypeRepository;
    }

    // getLookupTypeList Funtion To Get Lookup Type List
    public function getLookupTypeList()
    {
        return $this->lookupTypeRepository->getLookupTypeList();
    }

    // getLookupTypeDetails Funtion To Get Lookup Type Details
    public function getLookupTypeDetails(LookupType $lookupType)
    {
        return $this->lookupTypeRepository->getLookupTypeDetails($lookupType);
    }

    // addNewLookupType Funtion To Add new Lookup Type
    public function addNewLookupType(array $lookup_type_request)
    {
        return $this->lookupTypeRepository->addNewLookupType($this->prepareRequestInfo($lookup_type_request));
    }

    // updateLookupType Funtion To Update Lookup Type info
    public function updateLookupType(array $lookup_type_request, LookupType $lookupType)
    {
        // $lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($id);
        // if(!$lookup_type_details){
        //     return null;
        // }
        return $this->lookupTypeRepository->updateLookupType($lookupType,
            $this->prepareRequestInfo($lookup_type_request, true));
    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType(LookupType $lookupType)
    {
        // $lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($id);
        // if(!$lookup_type_details){
        //     return null;
        // }
        return $this->lookupTypeRepository->deleteLookupType($lookupType);
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {
        $request_data = [
            'type_en_name' => $request_info['type_en_name'] ?? null,
            'type_ar_name' => $request_info['type_ar_name'] ?? null,
            'type_description' => $request_info['type_description'] ?? null,
            'status' => $request_info['status'] ?? 1,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }
}
