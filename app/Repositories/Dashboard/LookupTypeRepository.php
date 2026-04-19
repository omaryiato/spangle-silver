<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class LookupTypeRepository
{

    // getLookupTypeList Funtion To Get Lookup Type List
    public function getLookupTypeList()
    {

        try {
            // Get Lookup Type
            $lookup_types = DB::table('lookup_types')->get();

            if ($lookup_types->isEmpty()) {
                return [];
            }

            $lookup_type_ids = $lookup_types->pluck('id');

            // Get Lookup Values
            $lookup_values = DB::table('lookup_values')
                ->whereIn('type_id', $lookup_type_ids)
                ->get()
                ->groupBy('type_id');

            // Merge
            $lookup_type_list = $lookup_types->map(function ($lookup_type) use ($lookup_values) {

                return [
                    'id' => $lookup_type->id,

                    'lookup_type_en_name' => $lookup_type->type_en_name,
                    'lookup_type_ar_name' => $lookup_type->type_ar_name,
                    'type_description' => $lookup_type->type_description,
                    'type_status' => (bool) $lookup_type->status,

                    'created_at' =>  $lookup_type->created_at,
                    'created_by' =>  $lookup_type->created_by,
                    'updated_at' =>  $lookup_type->updated_at,
                    'updated_by' =>  $lookup_type->updated_by,
                    'deleted_at' =>  $lookup_type->deleted_at,

                    // lookup_values
                    'lookup_values' => collect($lookup_values[$lookup_type->id] ?? [])
                                    ->values()
                                    ->map(function ($lookup_value) {
                                        return [
                                            'code' => (bool) $lookup_value->code,
                                            'meaning' => (bool) $lookup_value->meaning,
                                            'description' => (bool) $lookup_value->description,
                                            'status' => (bool) $lookup_value->status,
                                        ];
                                    })
                ];
            });

            return $lookup_type_list;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getLookupTypeDetails Funtion To Get Lookup Type Details
    public function getLookupTypeDetails($lookup_type_id)
    {

        try {
            // Get Lookup Type
            $lookup_type = DB::table('lookup_types')
                                ->whereId($lookup_type_id)
                                ->first();

            if ($lookup_type->isEmpty()) {
                return [];
            }

            $lookup_type_id = $lookup_type->pluck('id');

            // Get Lookup Values
            $lookup_values = DB::table('lookup_values')
                ->whereIn('type_id', $lookup_type_id)
                ->get()
                ->groupBy('type_id');

            // Merge
            $lookup_type_details = $lookup_type->map(function ($lookup_type) use ($lookup_values) {

                return [
                    'id' => $lookup_type->id,

                    'lookup_type_en_name' => $lookup_type->type_en_name,
                    'lookup_type_ar_name' => $lookup_type->type_ar_name,
                    'type_description' => $lookup_type->type_description,
                    'type_status' => (bool) $lookup_type->status,

                    'created_at' =>  $lookup_type->created_at,
                    'created_by' =>  $lookup_type->created_by,
                    'updated_at' =>  $lookup_type->updated_at,
                    'updated_by' =>  $lookup_type->updated_by,
                    'deleted_at' =>  $lookup_type->deleted_at,

                    // lookup_values
                    'lookup_values' => collect($lookup_values[$lookup_type->id] ?? [])
                                    ->values()
                                    ->map(function ($lookup_value) {
                                        return [
                                            'code' => (bool) $lookup_value->code,
                                            'meaning' => (bool) $lookup_value->meaning,
                                            'description' => (bool) $lookup_value->description,
                                            'status' => (bool) $lookup_value->status,
                                        ];
                                    })
                ];
            });

            return $lookup_type_details;

        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    // addNewLookupType Funtion To Add new Lookup Type
    public function addNewLookupType($lookup_type_details)
    {

        DB::beginTransaction();

        try {

            $lookup_type_id = DB::table('lookup_types')
                            ->insertGetId([
                                'type_en_name' => $lookup_type_details['lookup_type_en_name'],
                                'type_ar_name' => $lookup_type_details['lookup_type_ar_name'],
                                'type_description' => $lookup_type_details['lookup_type_description'],
                                'status' => $lookup_type_details['lookup_type_status'],
                                'created_by' => $lookup_type_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $lookup_type_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            return $lookup_type_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateLookupType Funtion To Update Lookup Type info
    public function updateLookupType($lookup_type_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('lookup_types')
                        ->whereId($lookup_type_details['lookup_type_id'])
                        ->update([
                                    "type_en_name" => $lookup_type_details['lookup_type_en_name'],
                                    "type_ar_name" => $lookup_type_details['lookup_type_ar_name'],
                                    "type_description" => $lookup_type_details['lookup_type_description'],
                                    "status" => $lookup_type_details['lookup_type_status'],
                                    "updated_by" => $lookup_type_details['login_user'],
                                    "updated_at" => now()
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType($lookup_type_id, $login_user)
    {

        DB::beginTransaction();

        try {

            DB::table('lookup_values')
                ->where('type_id',$lookup_type_id)
                ->update([
                    'status' => 0,
                    'updated_by' => $login_user,
                    'updated_at' => now(),
                    'deleted_at' => now()
                ]);

            return DB::table('lookup_types')
                        ->whereId($lookup_type_id)
                        ->update([
                            'status' => 0,
                            'updated_by' => $login_user,
                            'updated_at' => now(),
                            'deleted_at' => now()
                        ]);
            // return DB::table('lookup_types')
            //             ->whereId($lookup_type_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

