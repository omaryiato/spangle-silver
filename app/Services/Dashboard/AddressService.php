<?php

namespace App\Services\Dashboard;

use App\Models\Address;
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
        return $this->addressRepository->getAddressesList();
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails(Address $address)
    {
        return $this->addressRepository->getAddressDetails($address);
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress(array $address_request)
    {
        return $this->addressRepository->addNewAddress($this->prepareRequestInfo($address_request));
    }

    // updateAddress Funtion To Update Address info
    public function updateAddress(array $address_request, Address $address)
    {
        // $address_details = $this->addressRepository->getAddressDetails($id);
        // if(!$address_details){
        //     return null;
        // }
        return $this->addressRepository->updateAddress($address,
            $this->prepareRequestInfo($address_request, true));
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress(Address $address)
    {
        try {
            // $address_details = $this->addressRepository->getAddressDetails($id);
            // if(!$address_details){
            //     return null;
            // }
            return $this->addressRepository->deleteAddress($address);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {

        $request_data = [
            'user_id' => $request_info['user_id'] ?? null,
            'label' => $request_info['label'] ?? null,
            'full_name' => $request_info['full_name'] ?? null,
            'address_line' => $request_info['address_line'] ?? null,
            'city' => $request_info['city'] ?? null,
            'country' => $request_info['country'] ?? null,
            'postal_code' => $request_info['postal_code'] ?? null,
            'phone' => $request_info['phone'] ?? null,
            'is_default' => $request_info['is_default'] ?? 0,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $request_data;
    }

    public function getOwnedAddress(int $id, int $userId): Address
    {
        return $this->addressRepository->findOwned($id, $userId);
    }
}
