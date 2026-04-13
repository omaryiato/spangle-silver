<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ControlPanelFeatureRepository
{

    // getControlPanelFeaturesList Funtion To Get Control Panel Features List
    // public function getControlPanelFeaturesList($service_id)
    // {

    //     try {
    //         return DB::table('AJMI_FEATURES as ajf')
    //                 //->leftJoin('AJMI_PAGES as ajp', 'ajf.PAGE_ID', '=', 'ajp.ID')
    //                 ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
    //                 ->leftJoin('AJMI_EMPLOYEE_FEATURES as ajef', 'ajf.ID', '=', 'ajef.FEATURE_ID')
    //                 ->leftJoin('AJMI_ROLE_FEATURES as ajrf', 'ajf.ID', '=', 'ajrf.FEATURE_ID')
    //                 ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', 'ajrf.ROLE_ID', '=', 'ssur.SL_NO')
    //                 ->where(function ($query) use ($service_id) {
    //                     $query->where('ajf.SERVICE_ID', $service_id);
    //                 })
    //                 ->whereNull('ajf.DELETED_AT')
    //                 ->select(
    //                     'ajf.ID as feature_id',
    //                     'ajf.FEATURE_EN_NAME',
    //                     'ajf.FEATURE_AR_NAME',
    //                     'ajf.FEATURE_CODE',
    //                     'ajf.FEATURE_TYPE',
    //                     'ajf.FEATURE_STATUS',
    //                     'ajf.IS_DEFAULT AS feature_is_default',

    //                     // 'ajp.ID as page_id',
    //                     // 'ajp.PAGE_EN_NAME',
    //                     // 'ajp.PAGE_AR_NAME',

    //                     'ajs.ID as service_id',
    //                     'ajs.SERVICE_EN_NAME',
    //                     'ajs.SERVICE_AR_NAME',
    //                     'ajf.created_by',
    //                     'ajf.created_at',
    //                     'ajf.updated_by',
    //                     'ajf.updated_at',

    //                     'ajef.EMPLOYEE_NUMBER',
    //                     'ajef.ALLOW AS EMPLOYEE_ALLOW',
    //                     'ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE',
    //                     'ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE',

    //                     'ajrf.ROLE_ID',
    //                     'ssur.ROLE_EN_NAME',
    //                     'ssur.ROLE_AR_NAME',
    //                     'ajrf.ALLOW AS ROLE_ALLOW',
    //                     'ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE',
    //                     'ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE'
    //                 )
    //                 ->get();

    //         // $allowed_employee_feature = DB::select("SELECT FEATURE_ID
    //         //                                         FROM AJMI_EMPLOYEE_FEATURES
    //         //                                         WHERE EMPLOYEE_NUMBER = :employee_number
    //         //                                         AND ALLOW = :allow",
    //         //                                         [
    //         //                                             'employee_number' => $employee_number,
    //         //                                             'allow' => 1,
    //         //                                         ]);

    //         // $allowed_role_feature = DB::select("SELECT FEATURE_ID
    //         //                                         FROM AJMI_ROLE_FEATURES
    //         //                                         WHERE ROLE_ID = :role_id
    //         //                                         AND ALLOW = :allow",
    //         //                                         [
    //         //                                             'role_id' => $role_id,
    //         //                                             'allow' => 1,
    //         //                                         ]);

    //         // return DB::select("SELECT
    //         //                     ajf.ID as feature_id,
    //         //                     ajf.FEATURE_EN_NAME,
    //         //                     ajf.FEATURE_AR_NAME,
    //         //                     ajf.FEATURE_CODE,
    //         //                     ajf.FEATURE_TYPE,
    //         //                     ajf.FEATURE_STATUS,
    //         //                     ajf.IS_DEFAULT AS feature_is_default,

    //         //                     ajs.ID as service_id,
    //         //                     ajs.SERVICE_EN_NAME,
    //         //                     ajs.SERVICE_AR_NAME,

    //         //                     ajf.created_by,
    //         //                     ajf.created_at,
    //         //                     ajf.updated_by,
    //         //                     ajf.updated_at,

    //         //                     ajef.EMPLOYEE_NUMBER,
    //         //                     ajef.ALLOW AS EMPLOYEE_ALLOW,
    //         //                     ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE,
    //         //                     ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE,

    //         //                     ajrf.ROLE_ID,
    //         //                     ssur.ROLE_EN_NAME,
    //         //                     ssur.ROLE_AR_NAME,
    //         //                     ajrf.ALLOW AS ROLE_ALLOW,
    //         //                     ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE,
    //         //                     ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE

    //         //                 FROM AJMI_FEATURES ajf

    //         //                 LEFT JOIN AJMI_SERVICES ajs
    //         //                     ON ajf.SERVICE_ID = ajs.ID

    //         //                 LEFT JOIN AJMI_EMPLOYEE_FEATURES ajef
    //         //                     ON ajf.ID = ajef.FEATURE_ID

    //         //                 LEFT JOIN AJMI_ROLE_FEATURES ajrf
    //         //                     ON ajf.ID = ajrf.FEATURE_ID

    //         //                 LEFT JOIN SELF_SERVICE_USER_ROLES ssur
    //         //                     ON ajrf.ROLE_ID = ssur.SL_NO

    //         //                 WHERE ajf.DELETED_AT IS NULL
    //         //                 AND ajf.ID IN ()
    //         //             ");
    //     } catch (\Exception $exception) {
    //         throw $exception;
    //     }
    // }


    public function getControlPanelFeaturesList($service_id)
    {

        try {

            $features_list = DB::table('AJMI_FEATURES as ajf')
                            ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
                            ->where('ajf.SERVICE_ID', $service_id)
                            ->whereNull('ajf.DELETED_AT')
                            ->select(
                                'ajf.ID as feature_id',
                                'ajf.FEATURE_EN_NAME',
                                'ajf.FEATURE_AR_NAME',
                                'ajf.FEATURE_CODE',
                                'ajf.FEATURE_TYPE',
                                'ajf.FEATURE_STATUS',
                                'ajf.IS_DEFAULT AS feature_is_default',
                                'ajf.PARENT_ID AS feature_parent_id',

                                'ajs.ID as service_id',
                                'ajs.SERVICE_EN_NAME',
                                'ajs.SERVICE_AR_NAME',

                                'ajf.created_by',
                                'ajf.created_at',
                                'ajf.updated_by',
                                'ajf.updated_at'
                            )
                            ->get();

            $assined_role_features = DB::table('AJMI_ROLE_FEATURES as ajrf')
                            ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', 'ajrf.ROLE_ID', '=', 'ssur.SL_NO')
                            ->whereIn('ajrf.FEATURE_ID', $features_list->pluck('feature_id'))
                            ->get()
                            ->groupBy('FEATURE_ID');

            $assigned_employee_features = DB::table('AJMI_EMPLOYEE_FEATURES')
                                ->whereIn('FEATURE_ID', $features_list->pluck('feature_id'))
                                ->get()
                                ->groupBy('FEATURE_ID');

            $service_features_list = $features_list->map(function ($feature) use ($assigned_employee_features, $assined_role_features) {

                return (object)[
                    // feature info
                    'feature_id' => $feature->feature_id,
                    'feature_en_name' => $feature->feature_en_name,
                    'feature_ar_name' => $feature->feature_ar_name,
                    'feature_code' => $feature->feature_code,
                    'feature_type' => $feature->feature_type,
                    'feature_status' => $feature->feature_status,
                    'feature_is_default' => $feature->feature_is_default,

                    'service_id' => $feature->service_id,
                    'service_en_name' => $feature->service_en_name,
                    'service_ar_name' => $feature->service_ar_name,

                    'created_by' => $feature->created_by,
                    'created_at' => $feature->created_at,
                    'updated_by' => $feature->updated_by,
                    'updated_at' => $feature->updated_at,

                    // employees
                    'employees' => collect($assigned_employee_features[$feature->feature_id] ?? [])
                        ->map(function ($emp) {
                            return (object)[
                                'employee_number' => $emp->EMPLOYEE_NUMBER,
                                'allow' => $emp->ALLOW,
                                'assigned_from_date' => $emp->ASSIGNED_FROM_DATE,
                                'assigned_to_date' => $emp->ASSIGNED_TO_DATE,
                            ];
                        })
                        ->values(),


                    // roles
                    'roles' => collect($assined_role_features[$feature->feature_id] ?? [])
                        ->map(function ($role) {
                            return (object)[
                                'role_id' => $role->ROLE_ID,
                                'role_en_name' => $role->ROLE_EN_NAME,
                                'role_ar_name' => $role->ROLE_AR_NAME,
                                'allow' => $role->ALLOW,
                                'assigned_from_date' => $role->ASSIGNED_FROM_DATE,
                                'assigned_to_date' => $role->ASSIGNED_TO_DATE,
                            ];
                        })
                        ->values(),
                ];
            });

            return $service_features_list;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelFeatureDetails Funtion To Get Control Panel Feature Details
    public function getControlPanelFeatureDetails($feature_id)
    {

        try {

            $features_details = DB::table('AJMI_FEATURES as ajf')
                            ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
                            ->where('ajf.ID', $feature_id)
                            ->whereNull('ajf.DELETED_AT')
                            ->select(
                                'ajf.ID as feature_id',
                                'ajf.FEATURE_EN_NAME',
                                'ajf.FEATURE_AR_NAME',
                                'ajf.FEATURE_CODE',
                                'ajf.FEATURE_TYPE',
                                'ajf.FEATURE_STATUS',
                                'ajf.IS_DEFAULT AS feature_is_default',
                                'ajf.PARENT_ID AS feature_parent_id',

                                'ajs.ID as service_id',
                                'ajs.SERVICE_EN_NAME',
                                'ajs.SERVICE_AR_NAME',

                                'ajf.created_by',
                                'ajf.created_at',
                                'ajf.updated_by',
                                'ajf.updated_at'
                            )
                            ->get();

            $assined_role_features = DB::table('AJMI_ROLE_FEATURES as ajrf')
                            ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', 'ajrf.ROLE_ID', '=', 'ssur.SL_NO')
                            ->whereIn('ajrf.FEATURE_ID', $features_details->pluck('feature_id'))
                            ->get()
                            ->groupBy('FEATURE_ID');

            $assigned_employee_features = DB::table('AJMI_EMPLOYEE_FEATURES')
                                ->whereIn('FEATURE_ID', $features_details->pluck('feature_id'))
                                ->get()
                                ->groupBy('FEATURE_ID');

            $service_features_details = $features_details->map(function ($feature) use ($assigned_employee_features, $assined_role_features) {

                return (object)[
                    // feature info
                    'feature_id' => $feature->feature_id,
                    'feature_en_name' => $feature->feature_en_name,
                    'feature_ar_name' => $feature->feature_ar_name,
                    'feature_code' => $feature->feature_code,
                    'feature_type' => $feature->feature_type,
                    'feature_status' => $feature->feature_status,
                    'feature_is_default' => $feature->feature_is_default,

                    'service_id' => $feature->service_id,
                    'service_en_name' => $feature->service_en_name,
                    'service_ar_name' => $feature->service_ar_name,

                    'created_by' => $feature->created_by,
                    'created_at' => $feature->created_at,
                    'updated_by' => $feature->updated_by,
                    'updated_at' => $feature->updated_at,

                    // employees
                    'employees' => collect($assigned_employee_features[$feature->feature_id] ?? [])
                        ->map(function ($emp) {
                            return (object)[
                                'employee_number' => $emp->EMPLOYEE_NUMBER,
                                'allow' => $emp->ALLOW,
                                'assigned_from_date' => $emp->ASSIGNED_FROM_DATE,
                                'assigned_to_date' => $emp->ASSIGNED_TO_DATE,
                            ];
                        })
                        ->values(),


                    // roles
                    'roles' => collect($assined_role_features[$feature->feature_id] ?? [])
                        ->map(function ($role) {
                            return (object)[
                                'role_id' => $role->ROLE_ID,
                                'role_en_name' => $role->ROLE_EN_NAME,
                                'role_ar_name' => $role->ROLE_AR_NAME,
                                'allow' => $role->ALLOW,
                                'assigned_from_date' => $role->ASSIGNED_FROM_DATE,
                                'assigned_to_date' => $role->ASSIGNED_TO_DATE,
                            ];
                        })
                        ->values(),
                ];
            });

            return $service_features_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    // // getControlPanelFeatureDetails Funtion To Get Control Panel Feature Details
    // public function getControlPanelFeatureDetails($feature_id)
    // {

    //     try {
    //         return DB::table('AJMI_FEATURES as ajf')
    //                 ->leftJoin('AJMI_PAGES as ajp', 'ajf.PAGE_ID', '=', 'ajp.ID')
    //                 ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
    //                 ->leftJoin('AJMI_EMPLOYEE_FEATURES as ajef', 'ajf.ID', '=', 'ajef.FEATURE_ID')
    //                 ->leftJoin('AJMI_ROLE_FEATURES as ajrf', 'ajf.ID', '=', 'ajef.FEATURE_ID')
    //                 ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', 'ajrf.ROLE_ID', '=', 'ssur.SL_NO')
    //                 ->where(function ($query) use ($feature_id) {
    //                     $query->Where('ajf.ID', $feature_id);
    //                 })
    //                 ->whereNull('ajf.DELETED_AT')
    //                 ->select(
    //                     'ajf.ID as feature_id',
    //                     'ajf.FEATURE_EN_NAME',
    //                     'ajf.FEATURE_AR_NAME',
    //                     'ajf.FEATURE_CODE',
    //                     'ajf.FEATURE_TYPE',
    //                     'ajf.FEATURE_STATUS',
    //                     'ajf.IS_DEFAULT AS feature_is_default',

    //                     'ajp.ID as page_id',
    //                     'ajp.PAGE_EN_NAME',
    //                     'ajp.PAGE_AR_NAME',

    //                     'ajs.ID as service_id',
    //                     'ajs.SERVICE_EN_NAME',
    //                     'ajs.SERVICE_AR_NAME',
    //                     'ajf.created_by',
    //                     'ajf.created_at',
    //                     'ajf.updated_by',
    //                     'ajf.updated_at',

    //                     'ajef.EMPLOYEE_NUMBER',
    //                     'ajef.ALLOW AS EMPLOYEE_ALLOW',
    //                     'ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE',
    //                     'ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE',

    //                     'ajrf.ROLE_ID',
    //                     'ssur.ROLE_EN_NAME',
    //                     'ssur.ROLE_AR_NAME',
    //                     'ajrf.ALLOW AS ROLE_ALLOW',
    //                     'ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE',
    //                     'ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE'
    //                 )
    //                 ->get();
    //     } catch (\Exception $exception) {
    //         throw $exception;
    //     }
    // }

    // getAssignedEmployeesFeature Funtion to get the assigned employees on Feature
    public function getAssignedEmployeesFeature($employee_number)
    {

        try {

            return DB::table('AJMI_EMPLOYEE_FEATURES as ajef')
                    ->leftJoin('AJMI_FEATURES as ajf', 'ajef.FEATURE_ID', '=', 'ajf.ID')
                    ->leftJoin('AJMI_PAGES as ajp', 'ajf.PAGE_ID', '=', 'ajp.ID')
                    ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
                    ->where(function ($query) use ($employee_number) {
                        $query->Where('ajef.EMPLOYEE_NUMBER', $employee_number);
                    })
                    ->select(
                        'ajef.EMPLOYEE_NUMBER',
                        'ajef.ALLOW AS EMPLOYEE_ALLOW',
                        'ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE',
                        'ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE',

                        'ajf.ID as feature_id',
                        'ajf.FEATURE_EN_NAME',
                        'ajf.FEATURE_AR_NAME',
                        'ajf.FEATURE_CODE',
                        'ajf.FEATURE_TYPE',
                        'ajf.FEATURE_STATUS',
                        'ajf.IS_DEFAULT AS feature_is_default',

                        'ajp.ID as page_id',
                        'ajp.PAGE_EN_NAME',
                        'ajp.PAGE_AR_NAME',

                        'ajs.ID as service_id',
                        'ajs.SERVICE_EN_NAME',
                        'ajs.SERVICE_AR_NAME',
                        'ajf.created_by',
                        'ajf.created_at',
                        'ajf.updated_by',
                        'ajf.updated_at'
                    )
                    ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAssignedRolesFeature Funtion to get the assigned roles on Feature
    public function getAssignedRolesFeature($role_id)
    {

        try {
            return DB::table('AJMI_ROLE_FEATURES as ajrf')
                    ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', 'ajrf.ROLE_ID', '=', 'ssur.SL_NO')
                    ->leftJoin('AJMI_FEATURES as ajf', 'ajrf.FEATURE_ID', '=', 'ajf.ID')
                    ->leftJoin('AJMI_PAGES as ajp', 'ajf.PAGE_ID', '=', 'ajp.ID')
                    ->leftJoin('AJMI_SERVICES as ajs', 'ajf.SERVICE_ID', '=', 'ajs.ID')
                    ->where(function ($query) use ($role_id) {
                        $query->Where('ajrf.ROLE_ID', $role_id);
                    })
                    ->select(
                        'ajrf.ROLE_ID',
                        'ssur.ROLE_EN_NAME',
                        'ssur.ROLE_AR_NAME',
                        'ajrf.ALLOW AS ROLE_ALLOW',
                        'ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE',
                        'ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE',

                        'ajf.ID as feature_id',
                        'ajf.FEATURE_EN_NAME',
                        'ajf.FEATURE_AR_NAME',
                        'ajf.FEATURE_CODE',
                        'ajf.FEATURE_TYPE',
                        'ajf.FEATURE_STATUS',
                        'ajf.IS_DEFAULT AS feature_is_default',

                        'ajp.ID as page_id',
                        'ajp.PAGE_EN_NAME',
                        'ajp.PAGE_AR_NAME',

                        'ajs.ID as service_id',
                        'ajs.SERVICE_EN_NAME',
                        'ajs.SERVICE_AR_NAME',
                        'ajf.created_by',
                        'ajf.created_at',
                        'ajf.updated_by',
                        'ajf.updated_at'
                    )
                    ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelFeature Funtion To Add new Control Panel Feature
    public function addNewControlPanelFeature($feature_details)
    {

        DB::beginTransaction();

        try {

            if($feature_details['feature_parent_id'] == 0){
                $feature_details['feature_parent_id'] = DB::table('AJMI_FEATURES')
                                                            ->where('SERVICE_ID',$feature_details['service_id'])
                                                            ->where('FEATURE_TYPE', 'SERVICE')
                                                            ->value('ID');
            }

            $feature_id = DB::table('AJMI_FEATURES')->insertGetId([
                            'SERVICE_ID' => $feature_details['service_id'],
                            'PAGE_ID' => $feature_details['page_id'],
                            'FEATURE_EN_NAME' => $feature_details['feature_en_name'],
                            'FEATURE_AR_NAME' => $feature_details['feature_ar_name'],
                            'FEATURE_STATUS' => $feature_details['feature_status'],
                            'FEATURE_CODE' => $feature_details['feature_code'],
                            'FEATURE_TYPE' => $feature_details['feature_type'],
                            'IS_DEFAULT' => $feature_details['feature_is_default'],
                            'PARENT_ID' => $feature_details['feature_parent_id'],
                            'created_by' => $feature_details['login_user'],
                            'created_at' => now(),
                            'updated_by' => $feature_details['login_user'],
                            'updated_at' => now(),
                        ], 'ID');

            return $feature_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateControlPanelFeature Funtion To Update Control Panel Feature info
    public function updateControlPanelFeature($feature_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('AJMI_FEATURES')
                        ->whereId($feature_details['feature_id'])
                        ->update([
                            "FEATURE_EN_NAME" => $feature_details['feature_en_name'],
                            "FEATURE_AR_NAME" => $feature_details['feature_ar_name'],
                            "FEATURE_STATUS" => $feature_details['feature_status'],
                            "FEATURE_CODE"   => $feature_details['feature_code'],
                            "FEATURE_TYPE" => $feature_details['feature_type'],
                            "is_default" => $feature_details['feature_is_default'],
                            "updated_by" => $feature_details['login_user'],
                            "updated_at" => now()
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteControlPanelFeature Funtion To Delete Control Panel Feature
    public function deleteControlPanelFeature($feature_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('AJMI_FEATURES')
                        ->whereId($feature_id)
                        ->orWhere('PARENT_ID',$feature_id)
                        ->delete();
            // return DB::table('AJMI_FEATURES')
            //             ->whereId($feature_id)
            //             ->update([
            //                 'FEATURE_STATUS' => 0,
            //                 'updated_by' => $login_user,
            //                 'updated_at' => now(),
            //                 'deleted_at' => now()
            //             ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // assignFeature Funtion To Delete Control Panel Feature
    public function assignFeature($feature_id, $role, $employee_number, $login_user)
    {

        DB::beginTransaction();

        try {

            if (!empty($role)) {

                $current_roles = DB::table('AJMI_ROLE_FEATURES')
                                ->where('FEATURE_ID', $feature_id)
                                ->get()
                                ->keyBy('ROLE_ID')
                                ->toArray();

                $new_roles = collect($role)->keyBy('role_id')->toArray();

                $to_insert = [];
                $to_update = [];
                $to_delete = [];

                foreach ($new_roles as $role_id => $role_details) {

                    $formatted_data = [
                        'ROLE_ID' => $role_id,
                        'FEATURE_ID' => $feature_id,
                        'ALLOW' => $role_details['allow'],
                        'ASSIGNED_FROM_DATE' => Carbon::parse($role_details['assigned_from_date']),
                        'ASSIGNED_TO_DATE' => Carbon::parse($role_details['assigned_to_date']),
                        'created_by' => $login_user,
                        'created_at' => now(),
                    ];

                    if (!isset($current_roles[$role_id])) {
                        $to_insert[] = $formatted_data;
                    } else {
                        $old = (array) $current_roles[$role_id];

                        if (
                            $old['ALLOW'] != $role_details['allow'] ||
                            Carbon::parse($old['ASSIGNED_FROM_DATE'])->toDateTimeString() !==
                            $formatted_data['ASSIGNED_FROM_DATE']->toDateTimeString() ||
                            Carbon::parse($old['ASSIGNED_TO_DATE'])->toDateTimeString() !==
                            $formatted_data['ASSIGNED_TO_DATE']->toDateTimeString()
                        ) {
                            $to_update[] = $formatted_data;
                        }
                    }
                }

                foreach ($current_roles as $role_id => $old_role) {
                    if (!isset($new_roles[$role_id])) {
                        $to_delete[] = $role_id;
                    }
                }

                if (!empty($to_insert)) {
                    DB::table('AJMI_ROLE_FEATURES')->insert($to_insert);
                }

                foreach ($to_update as $row) {
                    DB::table('AJMI_ROLE_FEATURES')
                        ->where('ROLE_ID', $row['ROLE_ID'])
                        ->where('FEATURE_ID', $feature_id)
                        ->update([
                            'ALLOW' => $row['ALLOW'],
                            'ASSIGNED_FROM_DATE' => $row['ASSIGNED_FROM_DATE'],
                            'ASSIGNED_TO_DATE' => $row['ASSIGNED_TO_DATE'],
                        ]);
                }

                if (!empty($to_delete)) {
                    DB::table('AJMI_ROLE_FEATURES')
                        ->where('FEATURE_ID', $feature_id)
                        ->whereIn('ROLE_ID', $to_delete)
                        ->delete();
                }

            }

            if (!empty($employee_number)) {

                $current_employees = DB::table('AJMI_EMPLOYEE_FEATURES')
                                ->where('FEATURE_ID', $feature_id)
                                ->get()
                                ->keyBy('EMPLOYEE_NUMBER')
                                ->toArray();

                $new_employees = collect($employee_number)->keyBy('employee_number')->toArray();

                $to_insert = [];
                $to_update = [];
                $to_delete = [];

                foreach ($new_employees as $employee_number => $employee_details) {

                    $formatted_data = [
                        'EMPLOYEE_NUMBER' => $employee_number,
                        'FEATURE_ID' => $feature_id,
                        'ALLOW' => $employee_details['allow'],
                        'ASSIGNED_FROM_DATE' => Carbon::parse($employee_details['assigned_from_date']),
                        'ASSIGNED_TO_DATE' => Carbon::parse($employee_details['assigned_to_date']),
                        'created_by' => $login_user,
                        'created_at' => now(),
                    ];
                }

                foreach ($current_employees as $employee_number => $old_employee) {
                    if (!isset($new_employees[$employee_number])) {
                        $to_delete[] = $employee_number;
                    }
                }

                if (!empty($to_insert)) {
                    DB::table('AJMI_EMPLOYEE_FEATURES')->insert($to_insert);
                }

                foreach ($to_update as $row) {
                    DB::table('AJMI_EMPLOYEE_FEATURES')
                        ->where('EMPLOYEE_NUMBER', $row['EMPLOYEE_NUMBER'])
                        ->where('FEATURE_ID', $feature_id)
                        ->update([
                            'ALLOW' => $row['ALLOW'],
                            'ASSIGNED_FROM_DATE' => $row['ASSIGNED_FROM_DATE'],
                            'ASSIGNED_TO_DATE' => $row['ASSIGNED_TO_DATE'],
                        ]);
                }

                if (!empty($to_delete)) {
                    DB::table('AJMI_EMPLOYEE_FEATURES')
                        ->where('FEATURE_ID', $feature_id)
                        ->whereIn('EMPLOYEE_NUMBER', $to_delete)
                        ->delete();
                }
            }

            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // // assignFeature Funtion To Delete Control Panel Feature
    // public function assignFeature($feature_id, $role, $employee_number, $login_user)
    // {

    //     DB::beginTransaction();

    //     try {

    //         if (!empty($role)) {

    //             $data = [];

    //             foreach ($role as $role_details) {
    //                 $data[] = [
    //                     'ROLE_ID' => $role_details['role_id'],
    //                     'FEATURE_ID' => $feature_id,
    //                     'ALLOW' => $role_details['allow'],
    //                     'ASSIGNED_FROM_DATE' => Carbon::parse($role_details['assigned_from_date'])->format('Y-m-d H:i:s'),
    //                     'ASSIGNED_TO_DATE' => Carbon::parse($role_details['assigned_to_date'])->format('Y-m-d H:i:s'),
    //                     'created_by' => $login_user,
    //                     'created_at' => now(),
    //                 ];
    //             }

    //             DB::table('AJMI_ROLE_FEATURES')->insert($data);
    //         }

    //         if (!empty($employee_number)) {

    //             $data = [];

    //             foreach ($employee_number as $employee) {
    //                 $data[] = [
    //                     'EMPLOYEE_NUMBER' => $employee['employee_number'],
    //                     'FEATURE_ID' => $feature_id,
    //                     'ALLOW' => $employee['allow'],
    //                     'ASSIGNED_FROM_DATE' => Carbon::parse($employee['assigned_from_date'])->format('Y-m-d H:i:s'),
    //                     'ASSIGNED_TO_DATE' => Carbon::parse($employee['assigned_to_date'])->format('Y-m-d H:i:s'),
    //                     'created_by' => $login_user,
    //                     'created_at' => now(),
    //                 ];
    //             }

    //             DB::table('AJMI_EMPLOYEE_FEATURES')->insert($data);
    //         }

    //         return true;

    //     } catch (\Exception $exception) {
    //         throw $exception;
    //     }

    // }

    // unassignFeature Funtion To Delete Control Panel Feature
    public function unassignFeature($feature_id, $role, $employee_number, $login_user)
    {

        DB::beginTransaction();

        try {

            if (!empty($role)) {

                // delete from pivot table
                DB::table('AJMI_ROLE_FEATURES')
                    ->where('FEATURE_ID', $feature_id)
                    ->whereIn('ROLE_ID', $role)
                    ->delete();
            }

            if (!empty($employee_number)) {

                // delete from pivot table
                DB::table('AJMI_EMPLOYEE_FEATURES')
                    ->where('FEATURE_ID', $feature_id)
                    ->whereIn('EMPLOYEE_NUMBER', $employee_number)
                    ->delete();
            }

            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

