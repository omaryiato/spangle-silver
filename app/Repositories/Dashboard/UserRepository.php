<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class UserRepository
{

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        try {
            // Get Users
            $users = DB::table('users')->get();

            if ($users->isEmpty()) {
                return [];
            }

            $users_ids = $users->pluck('id');

            // Get Addresses
            $addresses = DB::table('addresses')
                ->whereIn('user_id', $users_ids)
                ->get()
                ->groupBy('user_id');

            // Merge
            $users_list = $users->map(function ($user) use ($addresses) {

                return [
                    'id' => $user->id,

                    'full_name' => $user->full_name,
                    'user_name' => $user->user_name,
                    'phone_number' => $user->phone_number,
                    'email_address' => $user->email_address,
                    'password' => $user->password,
                    'user_status' => (bool) $user->status,
                    'user_type' => $user->user_type,

                    'created_at' =>  $user->created_at,
                    'created_by' =>  $user->created_by,
                    'updated_at' =>  $user->updated_at,
                    'updated_by' =>  $user->updated_by,
                    'deleted_at' =>  $user->deleted_at,

                    // addresses
                    'user_addresses' => collect($addresses[$user->id] ?? [])
                                    ->values()
                                    ->map(function ($addresses) {
                                        return [
                                            'label' => $addresses->label,
                                            'full_name' => $addresses->full_name,
                                            'address_line' => $addresses->address_line,
                                            'city' => $addresses->city,
                                            'country' => $addresses->country,
                                            'postal_code' => $addresses->postal_code,
                                            'phone' => $addresses->phone,
                                            'is_default' => (bool) $addresses->is_default,
                                        ];
                                    })
                ];
            });

            return $users_list;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails($user_id)
    {

        try {
            // Get User
            $user = DB::table('users')
                        ->whereId($user_id)
                        ->first();

            $users_id = $user->pluck('id');

            // Get Addresses
            $addresses = DB::table('addresses')
                ->whereIn('user_id', $users_id)
                ->get()
                ->groupBy('user_id');

            // Merge
            $user_details = $user->map(function ($user) use ($addresses) {

                return [
                    'id' => $user->id,

                    'full_name' => $user->full_name,
                    'user_name' => $user->user_name,
                    'phone_number' => $user->phone_number,
                    'email_address' => $user->email_address,
                    'password' => $user->password,
                    'user_status' => (bool) $user->status,
                    'user_type' => $user->user_type,

                    'created_at' =>  $user->created_at,
                    'created_by' =>  $user->created_by,
                    'updated_at' =>  $user->updated_at,
                    'updated_by' =>  $user->updated_by,
                    'deleted_at' =>  $user->deleted_at,

                    // addresses
                    'user_addresses' => collect($addresses[$user->id] ?? [])
                                    ->values()
                                    ->map(function ($addresses) {
                                        return [
                                            'label' => $addresses->label,
                                            'full_name' => $addresses->full_name,
                                            'address_line' => $addresses->address_line,
                                            'city' => $addresses->city,
                                            'country' => $addresses->country,
                                            'postal_code' => $addresses->postal_code,
                                            'phone' => $addresses->phone,
                                            'is_default' => (bool) $addresses->is_default,
                                        ];
                                    })
                ];
            });

            return $user_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewUser Funtion To Add new User
    public function addNewUser($user_details)
    {

        DB::beginTransaction();

        try {

            $user_id = DB::table('users')
                            ->insertGetId([
                                'full_name' => $user_details['full_name'],
                                'user_name' => $user_details['user_name'],
                                'phone_number' => $user_details['phone_number'],
                                'email_address' => $user_details['email_address'],
                                'password' => $user_details['password'],
                                'status' => $user_details['user_status'],
                                'user_type' => $user_details['user_type'],
                                'created_by' => $user_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $user_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            return $user_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateUser Funtion To Update User info
    public function updateUser($user_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('users')
                ->whereId($user_details['user_id'])
                ->update([
                            'full_name' => $user_details['full_name'],
                            'user_name' => $user_details['user_name'],
                            'phone_number' => $user_details['phone_number'],
                            'email_address' => $user_details['email_address'],
                            'password' => $user_details['password'],
                            'status' => $user_details['user_status'],
                            'user_type' => $user_details['user_type'],
                            'updated_by' => $user_details['login_user'],
                            'updated_at' => now(),
                ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteUser Funtion To Delete User
    public function deleteUser($user_id, $login_user)
    {

        DB::beginTransaction();

        try {

            DB::table('addresses')
                ->where('user_id',$user_id)
                ->delete();

            return DB::table('users')
                        ->whereId($user_id)
                        ->update([
                            'status' => 0,
                            'updated_by' => $login_user,
                            'updated_at' => now(),
                            'deleted_at' => now()
                        ]);
            // return DB::table('users')
            //             ->whereId($user_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

