<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ControlPanelPageRepository
{

    // getControlPanelPagesList Funtion To Get Control Panel Pages List
    public function getControlPanelPagesList($service_id)
    {

        try {
            return  DB::table('AJMI_PAGES as ajp')
                    ->leftJoin('AJMI_SERVICES as ajs', function ($join) {
                        $join->on('ajp.SERVICE_ID', '=', 'ajs.ID')
                            ->whereNull('ajs.DELETED_AT');
                    })
                    ->leftJoin('AJMI_FEATURES as ajf', function ($join) {
                        $join->on('ajf.PAGE_ID', '=', 'ajp.ID')
                            ->whereNull('ajf.DELETED_AT');
                    })
                    ->leftJoin('AJMI_EMPLOYEE_FEATURES as ajef', function ($join) {
                        $join->on('ajf.ID', '=', 'ajef.FEATURE_ID');
                    })
                    ->leftJoin('AJMI_ROLE_FEATURES as ajrf', function ($join) {
                        $join->on('ajf.ID', '=', 'ajrf.FEATURE_ID');
                    })
                    ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', function ($join) {
                        $join->on('ajrf.ROLE_ID', '=', 'ssur.SL_NO');
                    })
                    ->where('ajp.SERVICE_ID', $service_id)
                    ->whereNull('ajp.DELETED_AT')
                    ->select(
                        'ajp.ID as page_id',
                        'ajp.SERVICE_ID',
                        'ajs.SERVICE_EN_NAME',
                        'ajs.SERVICE_AR_NAME',
                        'ajp.PAGE_EN_NAME',
                        'ajp.PAGE_AR_NAME',
                        'ajp.PAGE_CODE',
                        'ajp.PAGE_ROUTE',
                        'ajp.PAGE_STATUS',
                        'ajp.created_by',
                        'ajp.created_at',
                        'ajp.updated_by',
                        'ajp.updated_at',

                        'ajf.ID as feature_id',
                        'ajf.FEATURE_EN_NAME',
                        'ajf.FEATURE_AR_NAME',
                        'ajf.FEATURE_CODE',
                        'ajf.FEATURE_TYPE',
                        'ajf.FEATURE_STATUS',
                        'ajf.IS_DEFAULT AS feature_is_default',
                        'ajf.created_by AS feature_created_by',
                        'ajf.created_at AS feature_created_at',
                        'ajf.updated_by AS feature_updated_by',
                        'ajf.updated_at AS feature_updated_at',

                        'ajef.EMPLOYEE_NUMBER',
                        'ajef.ALLOW AS EMPLOYEE_ALLOW',
                        'ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE',
                        'ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE',

                        'ajrf.ROLE_ID',
                        'ssur.ROLE_EN_NAME',
                        'ssur.ROLE_AR_NAME',
                        'ajrf.ALLOW AS ROLE_ALLOW',
                        'ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE',
                        'ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE'
                    )
                    ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelPageDetails Funtion To Get Control Panel Page Details
    public function getControlPanelPageDetails($page_id)
    {

        try {
            return  DB::table('AJMI_PAGES as ajp')
                    ->leftJoin('AJMI_SERVICES as ajs', function ($join) {
                        $join->on('ajp.SERVICE_ID', '=', 'ajs.ID')
                            ->whereNull('ajs.DELETED_AT');
                    })
                    ->leftJoin('AJMI_FEATURES as ajf', function ($join) {
                        $join->on('ajf.PAGE_ID', '=', 'ajp.ID')
                            ->whereNull('ajf.DELETED_AT');
                    })
                    ->leftJoin('AJMI_EMPLOYEE_FEATURES as ajef', function ($join) {
                        $join->on('ajf.ID', '=', 'ajef.FEATURE_ID');
                    })
                    ->leftJoin('AJMI_ROLE_FEATURES as ajrf', function ($join) {
                        $join->on('ajf.ID', '=', 'ajrf.FEATURE_ID');
                    })
                    ->leftJoin('SELF_SERVICE_USER_ROLES as ssur', function ($join) {
                        $join->on('ajrf.ROLE_ID', '=', 'ssur.SL_NO');
                    })
                    ->where('ajp.ID', $page_id)
                    ->whereNull('ajp.DELETED_AT')
                    ->select(
                        'ajp.ID as page_id',
                        'ajp.SERVICE_ID',
                        'ajs.SERVICE_EN_NAME',
                        'ajs.SERVICE_AR_NAME',
                        'ajp.PAGE_EN_NAME',
                        'ajp.PAGE_AR_NAME',
                        'ajp.PAGE_CODE',
                        'ajp.PAGE_ROUTE',
                        'ajp.PAGE_STATUS',
                        'ajp.created_by',
                        'ajp.created_at',
                        'ajp.updated_by',
                        'ajp.updated_at',

                        'ajf.ID as feature_id',
                        'ajf.FEATURE_EN_NAME',
                        'ajf.FEATURE_AR_NAME',
                        'ajf.FEATURE_CODE',
                        'ajf.FEATURE_TYPE',
                        'ajf.FEATURE_STATUS',
                        'ajf.IS_DEFAULT AS feature_is_default',
                        'ajf.created_by AS feature_created_by',
                        'ajf.created_at AS feature_created_at',
                        'ajf.updated_by AS feature_updated_by',
                        'ajf.updated_at AS feature_updated_at',

                        'ajef.EMPLOYEE_NUMBER',
                        'ajef.ALLOW AS EMPLOYEE_ALLOW',
                        'ajef.ASSIGNED_FROM_DATE AS EMPLOYEE_ASSIGNED_FROM_DATE',
                        'ajef.ASSIGNED_TO_DATE AS EMPLOYEE_ASSIGNED_TO_DATE',

                        'ajrf.ROLE_ID',
                        'ssur.ROLE_EN_NAME',
                        'ssur.ROLE_AR_NAME',
                        'ajrf.ALLOW AS ROLE_ALLOW',
                        'ajrf.ASSIGNED_FROM_DATE AS ROLE_ASSIGNED_FROM_DATE',
                        'ajrf.ASSIGNED_TO_DATE AS ROLE_ASSIGNED_TO_DATE'
                    )
                    ->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelPage Funtion To Add new Control Panel Page
    public function addNewControlPanelPage($page_details)
    {

        DB::beginTransaction();

        try {

            $page_id = DB::table('AJMI_PAGES')->insertGetId([
                        'SERVICE_ID'   => $page_details['service_id'],
                        'PAGE_EN_NAME' => $page_details['page_en_name'],
                        'PAGE_AR_NAME' => $page_details['page_ar_name'],
                        'PAGE_STATUS'  => $page_details['page_status'],
                        'PAGE_CODE'    => $page_details['page_code'],
                        'PAGE_ROUTE'   => $page_details['page_route'],
                        'created_by'   => $page_details['login_user'],
                        'created_at'   => now(),
                        'updated_by'   => $page_details['login_user'],
                        'updated_at'   => now(),
                    ], 'ID');

            return $page_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateControlPanelPage Funtion To Update Control Panel Page info
    public function updateControlPanelPage($page_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('AJMI_PAGES')
                        ->whereId($page_details['page_id'])
                        ->update([
                            "PAGE_EN_NAME" => $page_details['page_en_name'],
                            "PAGE_AR_NAME" => $page_details['page_ar_name'],
                            "PAGE_STATUS" => $page_details['page_status'],
                            "PAGE_CODE" => $page_details['page_code'],
                            "PAGE_ROUTE" => $page_details['page_route'],
                            "updated_by" => $page_details['login_user'],
                            "updated_at" => now()
                        ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteControlPanelPage Funtion To Delete Control Panel Page
    public function deleteControlPanelPage($page_id, $login_user)
    {

        DB::beginTransaction();

        try {

            // return DB::table('AJMI_PAGES')
            //             ->whereId($page_id)
            //             ->update([
            //                 'PAGE_STATUS' => 0,
            //                 'updated_by' => $login_user,
            //                 'updated_at' => now(),
            //                 'deleted_at' => now(),
            //                 'page_id' => $page_id
            //             ]);

            return DB::table('AJMI_PAGES')
                        ->whereId($page_id)
                        ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

