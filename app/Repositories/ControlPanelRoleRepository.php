<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ControlPanelRoleRepository
{

    // getControlPanelRolesList Funtion To Get Control Panel Roles
    public function getControlPanelRolesList()
    {

        try {
            return DB::table('SELF_SERVICE_USER_ROLES')
                        ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelRoleDetails Funtion To Get Control Panel Role Details
    public function getControlPanelRoleDetails($role_id)
    {
        try {

            return DB::table('SELF_SERVICE_USER_ROLES')
                    ->where('SL_NO', $role_id)->first();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelRole Funtion To Add new Control Panel Role
    public function addNewControlPanelRole($role_details)
    {

        DB::beginTransaction();

        try {

            $new_role_id = DB::selectOne("SELECT SELF_SERVICE_USER_ROLES_SEQ.NEXTVAL as id FROM dual")->id;

            DB::table('SELF_SERVICE_USER_ROLES')
                ->insertGetId([
                    "SL_NO" => $new_role_id,
                    "ROLE" => $role_details['role_en_name'],
                    "ROLE_EN_NAME" => $role_details['role_en_name'],
                    "ROLE_AR_NAME" => $role_details['role_ar_name'],
                    "ROLE_CODE" => $role_details['role_code'],
                    "STATUS" => $role_details['role_status'],
                    "START_DATE" => now(),
                    "PROCESS_ROLE" => $role_details['role_en_name'],
                    "created_by" => $role_details['login_user'],
                    "creation_date" => now(),
                    "last_updated_by" => $role_details['login_user'],
                    "last_update_date" => now(),
                    "LAST_UPDATE_LOGIN" => $role_details['login_user'],
                ]);

            return $new_role_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateControlPanelRole Funtion To Update Control Panel Role info
    public function updateControlPanelRole($role_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('SELF_SERVICE_USER_ROLES')
                        ->where('SL_NO', $role_details['role_id'])
                        ->update([
                            "ROLE" => $role_details['role_en_name'],
                            "ROLE_EN_NAME" => $role_details['role_en_name'],
                            "ROLE_AR_NAME" => $role_details['role_ar_name'],
                            //"STATUS" => $role_details['role_status'],
                            "PROCESS_ROLE" => $role_details['role_en_name'],
                            "last_updated_by" => $role_details['login_user'],
                            "last_update_date" => now(),
                            "LAST_UPDATE_LOGIN" => $role_details['login_user'],
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteControlPanelRole Funtion To Delete Control Panel Role
    public function deleteControlPanelRole($role_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('SELF_SERVICE_USER_ROLES')
                        ->where('SL_NO', $role_id)
                        ->update([
                            'role_status' => 'F',
                            "last_updated_by" => $login_user,
                            "last_update_date" => now(),
                            "LAST_UPDATE_LOGIN" => $login_user,
                            "deleted_at" => now(),
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // assignEmployeeToRole Funtion To Assign New Employee to Role
    public function assignEmployeeToRole($role_id, $employee_number, $login_user)
    {

        DB::beginTransaction();

        try {

            $role_info = DB::table('SELF_SERVICE_USER_ROLES')
                ->where('SL_NO', $role_id)
                ->first();

            $employees = [];

            // decode existing employees
            if ($role_info && $role_info->employees) {
                $data = json_decode($role_info->employees, true);
                $employees = $data['employees'] ?? [];
            }

            // merge without duplicates
            $employees = array_unique(array_merge($employees, $employee_number));

            // update JSON column
            DB::table('SELF_SERVICE_USER_ROLES')
                ->where('SL_NO', $role_id)
                ->update([
                    'employees' => json_encode(['employees' => array_values($employees)]),
                    'last_updated_by' => $login_user,
                    'last_update_date' => now(),
                ]);

            // insert into pivot table
            foreach ($employee_number as $employee_number) {
                DB::table('AJMI_EMPLOYEE_ROLES')->insert([
                    'EMPLOYEE_NUMBER' => $employee_number,
                    'ROLE_ID' => $role_id,
                    'created_by' => $login_user,
                    'created_at' => now(),
                ]);
            }
            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // unassignEmployeeFromRole Funtion To Unassign Employee from Role
    public function unassignEmployeeFromRole($role_id, $employee_number, $login_user)
    {
        DB::beginTransaction();

        try {
            $role_info = DB::table('SELF_SERVICE_USER_ROLES')
                ->where('SL_NO', $role_id)
                ->first();

            $employees = [];

            if ($role_info && $role_info->employees) {
                $data = json_decode($role_info->employees, true);
                $employees = $data['employees'] ?? [];
            }

            // remove multiple employees
            $employees = array_values(array_diff($employees, $employee_number));

            // update JSON column
            DB::table('SELF_SERVICE_USER_ROLES')
                ->where('SL_NO', $role_id)
                ->update([
                    'employees' => json_encode(['employees' => $employees]),
                    'last_updated_by' => $login_user,
                    'last_update_date' => now(),
                ]);

            // delete from pivot table
            return DB::table('AJMI_EMPLOYEE_ROLES')
                ->where('ROLE_ID', $role_id)
                ->whereIn('EMPLOYEE_NUMBER', $employee_number)
                ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}

