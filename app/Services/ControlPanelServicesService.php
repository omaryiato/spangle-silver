<?php

namespace App\Services;

use App\Repositories\ControlPanelServiceRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class ControlPanelServicesService
{

    protected $controlPanelServiceRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    ControlPanelServiceRepository $controlPanelServiceRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->controlPanelServiceRepository = $controlPanelServiceRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getControlPanelServicesList Funtion To Get Control Panel Services List
    public function getControlPanelServicesList($role_id, $employee_number, $feature_type)
    {
        try {
            return  $this->controlPanelServiceRepository->getControlPanelServicesList($role_id, $employee_number, $feature_type);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelServiceDetails Funtion To Get Control Panel Service Details
    public function getControlPanelServiceDetails($service_id)
    {

        try {

            $service_details =  $this->controlPanelServiceRepository->getControlPanelServiceDetails($service_id);

            return $service_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelService Funtion To Add new Control Panel Service
    public function addNewControlPanelService($service_details)
    {

        try {

            $service_id = $this->controlPanelServiceRepository->addNewControlPanelService($service_details);
            $get_service_details = $this->controlPanelServiceRepository->getControlPanelServiceDetails($service_id);

            $feature_details = [
                "service_id" => $service_id,
                "page_id" => null,
                "feature_en_name" => $get_service_details->service_en_name . 'accessbility',
                "feature_ar_name" => $get_service_details->service_ar_name . 'امكانية الوصول',
                "feature_status" => 1,
                "feature_code" => $get_service_details->service_en_name . '.access',
                "feature_type" => 'SERVICE',
                "feature_is_default" => 0,
                "feature_parent_id" => null,
                "login_user" => $service_details['login_user'],
            ];
            $this->controlPanelFeatureService->addNewControlPanelFeature($feature_details);
            $this->mainControlPanelRepository->systemAuditLogs("CREATE", "add_new_service", "SERVICE", "null", $get_service_details, $service_details['login_user']);

            return $get_service_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateControlPanelService Funtion To Update Control Panel Service info
    public function updateControlPanelService($service_details)
    {

        try {
            $this->controlPanelServiceRepository->updateControlPanelService($service_details);
            $get_service_details = $this->controlPanelServiceRepository->getControlPanelServiceDetails($service_details['service_id']);

            $this->mainControlPanelRepository->systemAuditLogs("UPDATE", "update_service", "SERVICE", "null", $get_service_details, $service_details['login_user']);

            return $get_service_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteControlPanelService Funtion To Delete Control Panel Service
    public function deleteControlPanelService($service_id, $login_user)
    {
        try {

            $this->controlPanelServiceRepository->deleteControlPanelService($service_id, $login_user);
            return $this->mainControlPanelRepository->systemAuditLogs("DELETE", "delete_service", "SERVICE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

