<?php

namespace App\Services\Dashboard;

use App\Models\LookupType;
use App\Repositories\Dashboard\LookupTypeRepository;


class LookupTypeService
{

    protected $lookupTypeRepository;

    public function __construct(LookupTypeRepository $lookupTypeRepository)
    {
        $this->lookupTypeRepository = $lookupTypeRepository;
    }

    // getLookupTypeList Funtion To Get Lookup Type List
    public function getLookupTypeList()
    {
        return  $this->lookupTypeRepository->getLookupTypeList();
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
        return $this->lookupTypeRepository->updateLookupType( $lookupType,
                                    $this->prepareRequestInfo($lookup_type_request));
    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType($lookup_type_request, LookupType $lookupType)
    {
        // $lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($id);
        // if(!$lookup_type_details){
        //     return null;
        // }
        return $this->lookupTypeRepository->deleteLookupType($lookupType);
    }

    public function prepareRequestInfo(array $request_info)
    {
        $request_data = [
            'type_en_name' => $request_info['type_en_name'] ?? null,
            'type_ar_name' => $request_info['type_ar_name'] ?? null,
            'type_description' => $request_info['type_description'],
            'status' => $request_info['status'] ?? null,
        ];

        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }

        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }

        return $request_data;
    }

}

