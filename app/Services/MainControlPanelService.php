<?php

namespace App\Services;

use App\Repositories\MainControlPanelRepository;

class MainControlPanelService
{

    protected $mainControlPanelRepository;

    public function __construct(MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // controlPanelSystem Funtion To to Check User Validity
    public function controlPanelSystem($request)
    {
        try {

            $ip_address = null;
            // Check if HTTP_X_FORWARDED_FOR exists (may be set by proxy or load balancer)
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip_address =  trim($ipList[0]); // Return the first IP address in the list
            }

            // Check if HTTP_CLIENT_IP exists
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_address =  $_SERVER['HTTP_CLIENT_IP'];
            }

            // Default to REMOTE_ADDR (direct client IP)
            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip_address =  $_SERVER['REMOTE_ADDR'];
            }

            $hashkey = $request->hashkey;
            $user_role = $request->user_role;
            $source = 'WEB';
            $p_action_type = 'add';

            return  $this->mainControlPanelRepository->controlPanelSystem($hashkey, $ip_address, $source, $p_action_type, $user_role);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // checkIPAddress Funtion To to Check User Validity
    public function checkIPAddress($request)
    {
        try {

            $ip_address = null;

            $employee_number = $request->employee_number ?? null;
            $hashkey = $request->hashkey?? null;
            $user_role = $request->user_role?? null;
            $source = 'WEB'?? null;
            $p_action_type = 'add'?? null;

            return $this->mainControlPanelRepository->checkIPAddress($employee_number, $hashkey, $ip_address, $source, $p_action_type, $user_role);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getAllActiveEmployeeList Funtion To Get All Active Employees List
    public function getAllActiveEmployeeList()
    {
        try {
            return  $this->mainControlPanelRepository->getAllActiveEmployeeList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getEmployeeInformation Funtion To Get Employee Information
    public function getEmployeeInformation($employee_number)
    {
        try {

            return  $this->mainControlPanelRepository->getEmployeeInformation($employee_number);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getEmployeeAssignedFeature Funtion To Get Employee Assigned Feature
    public function getEmployeeAssignedFeature($service_id,$employee_number,$role_id)
    {
        try {

            return  $this->mainControlPanelRepository->getEmployeeAssignedFeature($service_id,$employee_number,$role_id);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // truncateAllTables Funtion to Delete all tables data
    public function truncateAllTables()
    {
        try {
            return $this->mainControlPanelRepository->truncateAllTables();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

