<?php

namespace App\Services\Dashboard;

use App\Models\LookupValue;
use App\Repositories\Dashboard\LookupValueRepository;


class LookupValueService
{

    protected $lookupValueRepository;

    public function __construct(LookupValueRepository $lookupValueRepository)
    {
        $this->lookupValueRepository = $lookupValueRepository;
    }

    // getLookupValueList Funtion To Get Lookup Value List
    public function getLookupValueList()
    {
        return  $this->lookupValueRepository->getLookupValueList();
    }

    // getLookupValueDetails Funtion To Get Lookup Value Details
    public function getLookupValueDetails(LookupValue $lookupValue)
    {
        $lookup_value_details =  $this->lookupValueRepository->getLookupValueDetails($lookupValue);
        if(!$lookup_value_details){
            return null;
        }
        return $lookup_value_details;
    }

    // addNewLookupValue Funtion To Add new Lookup Value
    public function addNewLookupValue(array $lookup_value_request)
    {

        return $this->lookupValueRepository->addNewLookupValue($this->prepareRequestInfo($lookup_value_request));

    }

    // updateLookupValue Funtion To Update Lookup Value info
    public function updateLookupValue(array $lookup_value_request, LookupValue $lookupValue)
    {
        // $lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($id);
        // if(!$lookup_value_details){
        //     return null;
        // }
        return $this->lookupValueRepository->updateLookupValue($lookupValue,
        $this->prepareRequestInfo($lookup_value_request));

    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function deleteLookupValue($lookup_type_request, LookupValue $lookupValue)
    {
        // $lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($id);
        // if(!$lookup_value_details){
        //     return null;
        // }
        return $this->lookupValueRepository->deleteLookupValue($lookupValue);
    }

    public function prepareRequestInfo(array $request_info)
    {

        $request_data = [
            'type_id' => $request_info['type_id'] ?? null,
            'code' => $request_info['code'] ?? null,
            'color' => $request_info['color'] ?? null,
            'en_meaning' => $request_info['en_meaning'] ?? null,
            'ar_meaning' => $request_info['ar_meaning'] ?? null,
            'description' => $request_info['description'] ?? null,
            'status' => $request_info['status'] ?? 1,
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

