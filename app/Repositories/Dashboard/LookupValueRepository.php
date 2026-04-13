<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class LookupValueRepository
{

    // getLookupValueList Funtion To Get Lookup Value List
    public function getLookupValueList()
    {

        try {
            return DB::table('LOOKUP_VALUES')->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getLookupValueDetails Funtion To Get Lookup Value Details
    public function getLookupValueDetails($lookup_value_id)
    {

        try {
            return DB::table('LOOKUP_VALUES')
                        ->whereId($lookup_value_id)
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewLookupValue Funtion To Add new Lookup Value
    public function addNewLookupValue($lookup_value_details)
    {

        DB::beginTransaction();

        try {

            $lookup_value_id = DB::table('LOOKUP_VALUES')
                            ->insertGetId([
                                'type_id' => $lookup_value_details['lookup_type_id'],
                                'code' => $lookup_value_details['lookup_value_code'],
                                'meaning' => $lookup_value_details['lookup_value_meaning'],
                                'description' => $lookup_value_details['lookup_value_description'],
                                'status' => $lookup_value_details['lookup_value_status'],
                                'created_by' => $lookup_value_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $lookup_value_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            return $lookup_value_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateLookupValue Funtion To Update Lookup Value info
    public function updateLookupValue($lookup_value_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('LOOKUP_VALUES')
                        ->whereId($lookup_value_details['lookup_value_id'])
                        ->update([
                                    "code" => $lookup_value_details['lookup_value_code'],
                                    "meaning" => $lookup_value_details['lookup_value_meaning'],
                                    "description" => $lookup_value_details['lookup_value_description'],
                                    "status" => $lookup_value_details['lookup_value_status'],
                                    "updated_by" => $lookup_value_details['login_user'],
                                    "updated_at" => now()
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function deleteLookupValue($lookup_value_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('LOOKUP_VALUES')
                        ->whereId($lookup_value_id)
                        ->update([
                            'status' => 0,
                            'updated_by' => $login_user,
                            'updated_at' => now(),
                            'deleted_at' => now()
                        ]);
            // return DB::table('LOOKUP_VALUES')
            //             ->whereId($lookup_value_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

