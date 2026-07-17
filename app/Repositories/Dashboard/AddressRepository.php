<?php

namespace App\Repositories\Dashboard;

use App\Models\Address;


class AddressRepository
{

    // getAddressesList Funtion To Get Addresses List
    public function getAddressesList()
    {
        return Address::with('user')->get();
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails(object $address)
    {
        return $address->load('user');
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress(array $address_request)
    {
        return Address::create($address_request);
    }

    // updateAddress Funtion To Update Address info
    public function updateAddress(object $address, array $address_request)
    {
        $address->update($address_request);
        return $address;
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress(object $address)
    {
        $address->delete();
        return $address;
    }
}

