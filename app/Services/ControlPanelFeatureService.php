<?php

namespace App\Services;

use App\Repositories\ControlPanelFeatureRepository;
use App\Repositories\MainControlPanelRepository;


class ControlPanelFeatureservice
{

    protected $controlPanelFeatureRepository;
    protected $mainControlPanelRepository;

    public function __construct(ControlPanelFeatureRepository $controlPanelFeatureRepository,
                                MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->controlPanelFeatureRepository = $controlPanelFeatureRepository;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getControlPanelFeaturesList Funtion To Get Control Panel Features List
    public function getControlPanelFeaturesList($service_id, $page_id)
    {
        try {
            $features_list =  $this->controlPanelFeatureRepository->getControlPanelFeaturesList($service_id);

            // $features = collect($features_list)
            //             ->groupBy('feature_id')
            //             ->map(function ($items) {

            //                 $feature = $items->first();

            //                 return (object)[
            //                     //  feature info
            //                     'feature_id' => $feature->feature_id,
            //                     'feature_en_name' => $feature->feature_en_name,
            //                     'feature_ar_name' => $feature->feature_ar_name,
            //                     'feature_code' => $feature->feature_code,
            //                     'feature_type' => $feature->feature_type,
            //                     'feature_status' => $feature->feature_status,
            //                     'feature_is_default' => $feature->feature_is_default,

            //                     'service_id' => $feature->service_id,
            //                     'service_en_name' => $feature->service_en_name,
            //                     'service_ar_name' => $feature->service_ar_name,

            //                     // 'page_id' => $feature->page_id,
            //                     // 'page_en_name' => $feature->page_en_name,
            //                     // 'page_ar_name' => $feature->page_ar_name,

            //                     'created_by' => $feature->created_by,
            //                     'created_at' => $feature->created_at,
            //                     'updated_by' => $feature->updated_by,
            //                     'updated_at' => $feature->updated_at,

            //                     //  employees
            //                     'employees' => $items
            //                         ->filter(fn($i) => $i->employee_number != null)
            //                         ->unique('employee_number')
            //                         ->map(function ($emp) {
            //                             return (object)[
            //                                 'employee_number' => $emp->employee_number,
            //                                 'allow' => $emp->employee_allow,
            //                                 'assigned_from_date' => $emp->employee_assigned_from_date,
            //                                 'assigned_to_date' => $emp->employee_assigned_to_date,
            //                             ];
            //                         })
            //                         ->values(),

            //                     //  roles
            //                     'roles' => $items
            //                         ->filter(fn($i) => $i->role_id != null)
            //                         ->unique('role_id')
            //                         ->map(function ($role) {
            //                             return (object)[
            //                                 'role_id' => $role->role_id,
            //                                 'role_en_name' => $role->role_en_name,
            //                                 'role_ar_name' => $role->role_ar_name,
            //                                 'allow' => $role->role_allow,
            //                                 'assigned_from_date' => $role->role_assigned_from_date,
            //                                 'assigned_to_date' => $role->role_assigned_to_date,
            //                             ];
            //                         })
            //                         ->values(),
            //                 ];
            //             })
            //             ->values();

            return $features_list;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelFeatureDetails Funtion To Get Control Panel Feature Details
    public function getControlPanelFeatureDetails($feature_id)
    {

        try {

            $feature_details =  $this->controlPanelFeatureRepository->getControlPanelFeatureDetails($feature_id);

            // $features = collect($feature_details)
            //             ->groupBy('feature_id')
            //             ->map(function ($items) {

            //                 $feature = $items->first();

            //                 return (object)[
            //                     // feature info
            //                     'feature_id' => $feature->feature_id,
            //                     'feature_en_name' => $feature->feature_en_name,
            //                     'feature_ar_name' => $feature->feature_ar_name,
            //                     'feature_code' => $feature->feature_code,
            //                     'feature_type' => $feature->feature_type,
            //                     'feature_status' => $feature->feature_status,
            //                     'feature_is_default' => $feature->feature_is_default,

            //                     'service_id' => $feature->service_id,
            //                     'service_en_name' => $feature->service_en_name,
            //                     'service_ar_name' => $feature->service_ar_name,

            //                     'page_id' => $feature->page_id,
            //                     'page_en_name' => $feature->page_en_name,
            //                     'page_ar_name' => $feature->page_ar_name,

            //                     'created_by' => $feature->created_by,
            //                     'created_at' => $feature->created_at,
            //                     'updated_by' => $feature->updated_by,
            //                     'updated_at' => $feature->updated_at,

            //                     // employees
            //                     'employees' => $items
            //                         ->filter(fn($i) => $i->employee_number != null)
            //                         ->unique('employee_number')
            //                         ->map(function ($emp) {
            //                             return (object)[
            //                                 'employee_number' => $emp->employee_number,
            //                                 'allow' => $emp->employee_allow,
            //                                 'assigned_from_date' => $emp->employee_assigned_from_date,
            //                                 'assigned_to_date' => $emp->employee_assigned_to_date,
            //                             ];
            //                         })
            //                         ->values(),

            //                     // roles
            //                     'roles' => $items
            //                         ->filter(fn($i) => $i->role_id != null)
            //                         ->unique('role_id')
            //                         ->map(function ($role) {
            //                             return (object)[
            //                                 'role_id' => $role->role_id,
            //                                 'role_en_name' => $role->role_en_name,
            //                                 'role_ar_name' => $role->role_ar_name,
            //                                 'allow' => $role->role_allow,
            //                                 'assigned_from_date' => $role->role_assigned_from_date,
            //                                 'assigned_to_date' => $role->role_assigned_to_date,
            //                             ];
            //                         })
            //                         ->values(),
            //                 ];
            //             })
            //             ->values();

            return $feature_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAssignedEmployeesFeature Funtion to get the assigned employees on Feature
    public function getAssignedEmployeesFeature($employee_number)
    {

        try {

            $assigned_feature_details =  $this->controlPanelFeatureRepository->getAssignedEmployeesFeature($employee_number);

            return $assigned_feature_details->values();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAssignedRolesFeature Funtion to get the assigned roles on Feature
    public function getAssignedRolesFeature($role_id)
    {

        try {

            $assigned_feature_details =  $this->controlPanelFeatureRepository->getAssignedRolesFeature($role_id);

            return $assigned_feature_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelFeature Funtion To Add new Control Panel Feature
    public function addNewControlPanelFeature($feature_details)
    {

        try {
            $feature_id = $this->controlPanelFeatureRepository->addNewControlPanelFeature($feature_details);
            $get_feature_details = $this->controlPanelFeatureRepository->getControlPanelFeatureDetails($feature_id);

            $this->mainControlPanelRepository->systemAuditLogs("CREATE", "add_new_feature", "FEATURE", "null", $get_feature_details, $feature_details['login_user']);

            return $get_feature_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateControlPanelFeature Funtion To Update Control Panel Feature info
    public function updateControlPanelFeature($feature_details)
    {

        try {

            $this->controlPanelFeatureRepository->updateControlPanelFeature($feature_details);
            $get_feature_details = $this->controlPanelFeatureRepository->updateControlPanelFeature($feature_details['feature_id']);

            $this->mainControlPanelRepository->systemAuditLogs("UPDATE", "update_feature", "FEATURE", "null", $get_feature_details, $feature_details['login_user']);

            return $get_feature_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteControlPanelFeature Funtion To Delete Control Panel Feature
    public function deleteControlPanelFeature($feature_id, $login_user)
    {

        try {

            $this->controlPanelFeatureRepository->deleteControlPanelFeature($feature_id, $login_user);

            return $this->mainControlPanelRepository->systemAuditLogs("DELETE", "delete_feature", "FEATURE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // assignFeature Funtion To Assign Control Panel Feature
    public function assignFeature($feature_id, $role, $employee_number, $login_user)
    {

        try {

            $this->controlPanelFeatureRepository->assignFeature($feature_id, $role, $employee_number, $login_user);
            return  $this->mainControlPanelRepository->systemAuditLogs("ASSIGN", "assign_feature", "FEATURE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // unassignFeature Funtion To Unassign Control Panel Feature
    public function unassignFeature($feature_id, $role, $employee_number, $login_user)
    {

        try {

            $this->controlPanelFeatureRepository->unassignFeature($feature_id, $role, $employee_number, $login_user);
            return  $this->mainControlPanelRepository->systemAuditLogs("UNASSIGN", "unassign_feature", "FEATURE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

