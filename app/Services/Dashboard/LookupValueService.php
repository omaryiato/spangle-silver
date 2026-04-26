<?php

namespace App\Services\Dashboard;

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
    public function getLookupValueDetails($id)
    {
        $lookup_value_details =  $this->lookupValueRepository->getLookupValueDetails($id);
        return $lookup_value_details;
    }

    // addNewLookupValue Funtion To Add new Lookup Value
    public function addNewLookupValue($lookup_value_details)
    {

        return $this->lookupValueRepository->addNewLookupValue($lookup_value_details);

    }

    // updateLookupValue Funtion To Update Lookup Value info
    public function updateLookupValue($lookup_value_request, $id)
    {
        $lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($id);
        return $this->lookupValueRepository->updateLookupValue($lookup_value_details, $lookup_value_request);

    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function deleteLookupValue($lookup_type_request, $id)
    {
        $lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($id);
        return $this->lookupValueRepository->deleteLookupValue($lookup_value_details);
    }

}

