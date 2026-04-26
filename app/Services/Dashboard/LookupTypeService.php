<?php

namespace App\Services\Dashboard;

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
    public function getLookupTypeDetails($id)
    {

        $lookup_type_details =  $this->lookupTypeRepository->getLookupTypeDetails($id);

        return $lookup_type_details;
    }

    // addNewLookupType Funtion To Add new Lookup Type
    public function addNewLookupType($lookup_type_request)
    {
        return $this->lookupTypeRepository->addNewLookupType($lookup_type_request);
    }

    // updateLookupType Funtion To Update Lookup Type info
    public function updateLookupType($lookup_type_request, $id)
    {
        $lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($id);
        return $this->lookupTypeRepository->updateLookupType($lookup_type_details, $lookup_type_request);
    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType($lookup_type_request, $id)
    {
        $lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($id);
        return $this->lookupTypeRepository->deleteLookupType($lookup_type_details);
    }

}

