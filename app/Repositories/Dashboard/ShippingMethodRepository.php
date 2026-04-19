<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ShippingMethodRepository
{

    // getShippingMethodsList Funtion To Get Shipping Methods List
    public function getShippingMethodsList()
    {

        try {
            return DB::table('shipping_methods')->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getShippingMethodDetails Funtion To Get Shipping Method Details
    public function getShippingMethodDetails($shipping_method_id)
    {

        try {
            return DB::table('shipping_methods')
                        ->whereId($shipping_method_id)
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewShippingMethod Funtion To Add new Shipping Method
    public function addNewShippingMethod($shipping_method_details)
    {

        DB::beginTransaction();

        try {
            $shipping_method_id = DB::table('shipping_methods')
                            ->insertGetId([
                                'method_en_name' => $shipping_method_details['method_en_name'],
                                'method_ar_name' => $shipping_method_details['method_ar_name'],
                                'price' => $shipping_method_details['method_price'],
                                'estimated_days' => $shipping_method_details['method_estimated_days'],
                                'status' => $shipping_method_details['method_status'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ], 'ID');

            return $shipping_method_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod($shipping_method_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('shipping_methods')
                ->whereId($shipping_method_details['shipping_method_id'])
                ->update([
                            'method_en_name' => $shipping_method_details['method_en_name'],
                            'method_ar_name' => $shipping_method_details['method_ar_name'],
                            'price' => $shipping_method_details['method_price'],
                            'estimated_days' => $shipping_method_details['method_estimated_days'],
                            'status' => $shipping_method_details['method_status'],
                            'updated_at' => now(),
                ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod($shipping_method_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('shipping_methods')
                        ->whereId($shipping_method_id)
                        ->update([
                            'status' => 0,
                            'updated_at' => now()
                        ]);
            // return DB::table('shipping_methods')
            //             ->whereId($shipping_method_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

