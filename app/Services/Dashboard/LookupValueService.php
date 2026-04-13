<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\LookupValueRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class LookupValueService
{

    protected $lookupValueRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    LookupValueRepository $lookupValueRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->lookupValueRepository = $lookupValueRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getLookupValueList Funtion To Get Lookup Value List
    public function getLookupValueList()
    {
        try {
            return  $this->lookupValueRepository->getLookupValueList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getLookupValueDetails Funtion To Get Lookup Value Details
    public function getLookupValueDetails($lookup_value_id)
    {

        try {

            $lookup_value_details =  $this->lookupValueRepository->getLookupValueDetails($lookup_value_id);

            return $lookup_value_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewLookupValue Funtion To Add new Lookup Value
    public function addNewLookupValue($lookup_value_details)
    {

        try {

            $lookup_value_id = $this->lookupValueRepository->addNewLookupValue($lookup_value_details);
            $get_lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($lookup_value_id);

            return $get_lookup_value_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateLookupValue Funtion To Update Lookup Value info
    public function updateLookupValue($lookup_value_details)
    {

        try {
            $this->lookupValueRepository->updateLookupValue($lookup_value_details);
            $get_lookup_value_details = $this->lookupValueRepository->getLookupValueDetails($lookup_value_details['lookup_value_id']);

            return $get_lookup_value_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function deleteLookupValue($lookup_value_id, $login_user)
    {
        try {

            return $this->lookupValueRepository->deleteLookupValue($lookup_value_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

