<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\AddressRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class AddressService
{

    protected $addressRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    AddressRepository $addressRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getAddressesList Funtion To Get Addresses List
    public function getAddressesList($user_id)
    {
        try {
            return  $this->addressRepository->getAddressesList($user_id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails($address_id)
    {

        try {

            $address_details =  $this->addressRepository->getAddressDetails($address_id);

            return $address_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress($address_details)
    {

        try {

            $address_id = $this->addressRepository->addNewAddress($address_details);
            $get_address_details = $this->addressRepository->getAddressDetails($address_id);

            return $get_address_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateAddress Funtion To Update Address info
    public function updateAddress($address_details)
    {

        try {
            $this->addressRepository->updateAddress($address_details);
            $get_address_details = $this->addressRepository->getAddressDetails($address_details['address_id']);

            return $get_address_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress($address_id, $login_user)
    {
        try {

            return $this->addressRepository->deleteAddress($address_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

