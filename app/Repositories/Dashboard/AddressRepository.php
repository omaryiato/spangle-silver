<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class AddressRepository
{

    // getAddressesList Funtion To Get Addresses List
    public function getAddressesList($user_id)
    {
        try {
            return DB::table('ADDRESSES')
                        ->where('user_id', $user_id)
                        ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAddressDetails Funtion To Get Address Details
    public function getAddressDetails($address_id)
    {

        try {
            return DB::table('ADDRESSES')
                        ->whereId($address_id)
                        ->first();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewAddress Funtion To Add new Address
    public function addNewAddress($address_details)
    {

        DB::beginTransaction();

        try {

            $address_id = DB::table('ADDRESSES')
                            ->insertGetId([
                                'user_id' => $address_details['user_id'],
                                'label' => $address_details['label'],
                                'full_name' => $address_details['full_name'],
                                'address_line' => $address_details['address_line'],
                                'city' => $address_details['city'],
                                'country' => $address_details['country'],
                                'postal_code' => $address_details['user_postal_code'],
                                'phone' => $address_details['phone'],
                                'is_default' => $address_details['is_default'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ], 'ID');

            return $address_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateAddress Funtion To Update Address info
    public function updateAddress($address_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('ADDRESSES')
                ->whereId($address_details['address_id'])
                ->update([
                            'label' => $address_details['label'],
                            'full_name' => $address_details['full_name'],
                            'address_line' => $address_details['address_line'],
                            'city' => $address_details['city'],
                            'country' => $address_details['country'],
                            'postal_code' => $address_details['user_postal_code'],
                            'phone' => $address_details['phone'],
                            'is_default' => $address_details['is_default'],
                            'updated_at' => now(),
                ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress($address_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('ADDRESSES')
                ->where('address_id',$address_id)
                ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

