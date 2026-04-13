<?php

namespace App\Services;

use App\Repositories\ControlPanelPageRepository;
use App\Services\ControlPanelFeatureService;
use App\Repositories\MainControlPanelRepository;



class ControlPanelPageService
{

    protected $controlPanelPageRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;


    public function __construct(ControlPanelPageRepository $controlPanelPageRepository,
                                ControlPanelFeatureservice $controlPanelFeatureService,
                                MainControlPanelRepository $mainControlPanelRepository
                                )
    {
        $this->controlPanelPageRepository = $controlPanelPageRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getControlPanelPagesList Funtion To Get Control Panel Pages List
    public function getControlPanelPagesList($service_id)
    {
        try {

            $pages_list =  $this->controlPanelPageRepository->getControlPanelPagesList($service_id);

            //  grouping
            $pages = collect($pages_list)->groupBy('page_id')->map(function ($items) {

            $page = $items->first();

            return (object) [
                    'service_id' => $page->service_id,
                    'service_en_name' => $page->service_en_name,
                    'service_ar_name' => $page->service_ar_name,

                    'page_id' => $page->page_id,
                    'page_en_name' => $page->page_en_name,
                    'page_ar_name' => $page->page_ar_name,
                    'page_code' => $page->page_code,
                    'page_route' => $page->page_route,
                    'page_status' => $page->page_status,
                    'created_by' => $page->created_by,
                    'created_at' => $page->created_at,
                    'updated_by' => $page->updated_by,
                    'updated_at' => $page->updated_at,

                    'features' => collect($items)
                                ->filter(fn($i) => $i->feature_id != null)
                                ->unique('feature_id')
                                ->map(function ($feature) use ($page,$items) {
                                    return (object) [
                                        'service_id' => $page->service_id,
                                        'service_en_name' => $page->service_en_name,
                                        'service_ar_name' => $page->service_ar_name,

                                        'page_id' => $page->page_id,
                                        'page_en_name' => $page->page_en_name,
                                        'page_ar_name' => $page->page_ar_name,

                                        'feature_id' => $feature->feature_id,
                                        'feature_en_name' => $feature->feature_en_name,
                                        'feature_ar_name' => $feature->feature_ar_name,
                                        'feature_code' => $feature->feature_code,
                                        'feature_type' => $feature->feature_type,
                                        'feature_status' => $feature->feature_status,
                                        'feature_is_default' => $feature->feature_is_default,
                                        'created_by' => $feature->created_by,
                                        'created_at' => $feature->created_at,
                                        'updated_by' => $feature->updated_by,
                                        'updated_at' => $feature->updated_at,

                                        //  employees
                                        'employees' => $items
                                            ->filter(fn($i) => $i->employee_number != null)
                                            ->unique('employee_number')
                                            ->map(function ($emp) {
                                                return (object)[
                                                    'employee_number' => $emp->employee_number,
                                                    'allow' => $emp->employee_allow,
                                                    'assigned_from_date' => $emp->employee_assigned_from_date,
                                                    'assigned_to_date' => $emp->employee_assigned_to_date,
                                                ];
                                            })
                                            ->values(),

                                        //  roles
                                        'roles' => $items
                                            ->filter(fn($i) => $i->role_id != null)
                                            ->unique('role_id')
                                            ->map(function ($role) {
                                                return (object)[
                                                    'role_id' => $role->role_id,
                                                    'role_en_name' => $role->role_en_name,
                                                    'role_ar_name' => $role->role_ar_name,
                                                    'allow' => $role->role_allow,
                                                    'assigned_from_date' => $role->role_assigned_from_date,
                                                    'assigned_to_date' => $role->role_assigned_to_date,
                                                ];
                                            })
                                            ->values(),
                                            ];
                                })
                                ->values()
                ];
            })->values();

            return $pages;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelPageDetails Funtion To Get Control Panel Page Details
    public function getControlPanelPageDetails($page_id)
    {

        try {

            $page_details =  $this->controlPanelPageRepository->getControlPanelPageDetails($page_id);

            //  grouping
            $pages = collect($page_details)->groupBy('page_id')->map(function ($items) {

            $page = $items->first();

            return (object) [
                    'service_id' => $page->service_id,
                    'service_en_name' => $page->service_en_name,
                    'service_ar_name' => $page->service_ar_name,

                    'page_id' => $page->page_id,
                    'page_en_name' => $page->page_en_name,
                    'page_ar_name' => $page->page_ar_name,
                    'page_code' => $page->page_code,
                    'page_route' => $page->page_route,
                    'page_status' => $page->page_status,
                    'created_by' => $page->created_by,
                    'created_at' => $page->created_at,
                    'updated_by' => $page->updated_by,
                    'updated_at' => $page->updated_at,

                    'features' => collect($items)
                                ->filter(fn($i) => $i->feature_id != null)
                                ->unique('feature_id')
                                ->map(function ($feature) use ($page,$items) {
                                    return (object) [
                                        'service_id' => $page->service_id,
                                        'service_en_name' => $page->service_en_name,
                                        'service_ar_name' => $page->service_ar_name,

                                        'page_id' => $page->page_id,
                                        'page_en_name' => $page->page_en_name,
                                        'page_ar_name' => $page->page_ar_name,

                                        'feature_id' => $feature->feature_id,
                                        'feature_en_name' => $feature->feature_en_name,
                                        'feature_ar_name' => $feature->feature_ar_name,
                                        'feature_code' => $feature->feature_code,
                                        'feature_type' => $feature->feature_type,
                                        'feature_status' => $feature->feature_status,
                                        'feature_is_default' => $feature->feature_is_default,
                                        'created_by' => $feature->created_by,
                                        'created_at' => $feature->created_at,
                                        'updated_by' => $feature->updated_by,
                                        'updated_at' => $feature->updated_at,

                                        //  employees
                                        'employees' => $items
                                            ->filter(fn($i) => $i->employee_number != null)
                                            ->unique('employee_number')
                                            ->map(function ($emp) {
                                                return (object)[
                                                    'employee_number' => $emp->employee_number,
                                                    'allow' => $emp->employee_allow,
                                                    'assigned_from_date' => $emp->employee_assigned_from_date,
                                                    'assigned_to_date' => $emp->employee_assigned_to_date,
                                                ];
                                            })
                                            ->values(),

                                        //  roles
                                        'roles' => $items
                                            ->filter(fn($i) => $i->role_id != null)
                                            ->unique('role_id')
                                            ->map(function ($role) {
                                                return (object)[
                                                    'role_id' => $role->role_id,
                                                    'role_en_name' => $role->role_en_name,
                                                    'role_ar_name' => $role->role_ar_name,
                                                    'allow' => $role->role_allow,
                                                    'assigned_from_date' => $role->role_assigned_from_date,
                                                    'assigned_to_date' => $role->role_assigned_to_date,
                                                ];
                                            })
                                            ->values(),
                                    ];
                                })
                                ->values()
                ];
            })->values();

            return $pages;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelPage Funtion To Add new Control Panel Page
    public function addNewControlPanelPage($page_details)
    {

        try {

            $page_id = $this->controlPanelPageRepository->addNewControlPanelPage($page_details);
            $get_page_details = $this->controlPanelPageRepository->getControlPanelPageDetails($page_id);

            $feature_details = [
                "service_id" => $get_page_details->service_id,
                "page_id" => $page_id,
                "feature_en_name" => $get_page_details->page_en_name . 'accessbility',
                "feature_ar_name" => $get_page_details->page_ar_name . 'امكانية الوصول',
                "feature_status" => 1,
                "feature_code" => $get_page_details->page_en_name . '.access',
                "feature_type" => 'PAGE',
                "feature_is_default" => 0,
                "feature_parent_id" => 0,
                "login_user" => $page_details['login_user'],
            ];

            $this->controlPanelFeatureService->addNewControlPanelFeature($feature_details);

            $this->mainControlPanelRepository->systemAuditLogs("CREATE", "add_new_page", "PAGE", "null", $get_page_details, $page_details['login_user']);

            return $get_page_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateControlPanelPage Funtion To Update Control Panel Page info
    public function updateControlPanelPage($page_details)
    {

        try {
            $this->controlPanelPageRepository->updateControlPanelPage($page_details);
            $get_page_details = $this->controlPanelPageRepository->getControlPanelPageDetails($page_details['page_id']);
            $this->mainControlPanelRepository->systemAuditLogs("UPDATE", "update_page", "PAGE", "null", $get_page_details, $page_details['login_user']);

            return  $get_page_details;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteControlPanelPage Funtion To Delete Control Panel Page
    public function deleteControlPanelPage($page_id, $login_user)
    {

        try {

            $this->controlPanelPageRepository->deleteControlPanelPage($page_id, $login_user);
            return $this->mainControlPanelRepository->systemAuditLogs("DELETE", "delete_page", "PAGE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

