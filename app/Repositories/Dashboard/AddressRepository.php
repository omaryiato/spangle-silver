<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\Address;


class AddressRepository
{

    // getAddressesList Funtion To Get Addresses List
    public function getAddressesList()
    {
        try {
            return Address::all();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails($id)
    {

        try {
            return Address::findorfail($id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress($address_request)
    {

        return Address::create($address_request);

    }

    // updateAddress Funtion To Update Address info
    public function updateAddress(Address $address, $address_request)
    {
        $address->update($address_request);
        return $address;
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress(Address $address)
    {
        $address->delete();
        return $address;
    }
}

