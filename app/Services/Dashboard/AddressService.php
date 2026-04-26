<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\AddressRepository;

class AddressService
{

    protected $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    // Funtion To Get Addresses List
    public function getAddressesList()
    {
        return  $this->addressRepository->getAddressesList();
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails($id)
    {
        return $this->addressRepository->getAddressDetails($id);
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress($address_request)
    {
        return $this->addressRepository->addNewAddress($address_request);
    }

    // updateAddress Funtion To Update Address info
    public function updateAddress($address_request, $id)
    {
        $address_details = $this->addressRepository->getAddressDetails($id);
        return $this->addressRepository->updateAddress($address_details, $address_request);
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress($address_request, $id)
    {
        try {
            $address_details = $this->addressRepository->getAddressDetails($id);
            return $this->addressRepository->deleteAddress($address_details);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

