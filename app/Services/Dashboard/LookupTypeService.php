<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\LookupTypeRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class LookupTypeService
{

    protected $lookupTypeRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    LookupTypeRepository $lookupTypeRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->lookupTypeRepository = $lookupTypeRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getLookupTypeList Funtion To Get Lookup Type List
    public function getLookupTypeList()
    {
        try {
            return  $this->lookupTypeRepository->getLookupTypeList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getLookupTypeDetails Funtion To Get Lookup Type Details
    public function getLookupTypeDetails($lookup_type_id)
    {

        try {

            $lookup_type_details =  $this->lookupTypeRepository->getLookupTypeDetails($lookup_type_id);

            return $lookup_type_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewLookupType Funtion To Add new Lookup Type
    public function addNewLookupType($lookup_type_details)
    {

        try {

            $lookup_type_id = $this->lookupTypeRepository->addNewLookupType($lookup_type_details);
            $get_lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($lookup_type_id);

            return $get_lookup_type_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateLookupType Funtion To Update Lookup Type info
    public function updateLookupType($lookup_type_details)
    {

        try {
            $this->lookupTypeRepository->updateLookupType($lookup_type_details);
            $get_lookup_type_details = $this->lookupTypeRepository->getLookupTypeDetails($lookup_type_details['lookup_type_id']);

            return $get_lookup_type_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType($lookup_type_id, $login_user)
    {
        try {

            return $this->lookupTypeRepository->deleteLookupType($lookup_type_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

