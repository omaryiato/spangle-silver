<?php

namespace App\Services;

use App\Repositories\ControlPanelRoleRepository;
use App\Repositories\MainControlPanelRepository;


class ControlPanelRoleService
{

    protected $controlPanelRoleRepository;
    protected $mainControlPanelRepository;

    public function __construct(ControlPanelRoleRepository $controlPanelRoleRepository,
                                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->controlPanelRoleRepository = $controlPanelRoleRepository;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getControlPanelRolesList Funtion To Get Control Panel Roles List
    public function getControlPanelRolesList()
    {
        try {
            $all_roles_list =  $this->controlPanelRoleRepository->getControlPanelRolesList();
            return $all_roles_list;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelRoleDetails Funtion To Get Control Panel Role Details
    public function getControlPanelRoleDetails($role_id)
    {

        try {

            $role_details =  $this->controlPanelRoleRepository->getControlPanelRoleDetails($role_id);
            return $role_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelRole Funtion To Add new Control Panel Role
    public function addNewControlPanelRole($role_details)
    {

        try {

            $role_id = $this->controlPanelRoleRepository->addNewControlPanelRole($role_details);
            $get_role_details = $this->controlPanelRoleRepository->getControlPanelRoleDetails($role_id);

            $this->mainControlPanelRepository->systemAuditLogs("CREATE", "add_new_role", "ROLE", "null", $get_role_details, $role_details['login_user']);

            return $get_role_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateControlPanelRole Funtion To Update Control Panel Role info
    public function updateControlPanelRole($role_details)
    {

        try {

            $this->controlPanelRoleRepository->updateControlPanelRole($role_details);
            $get_role_details = $this->controlPanelRoleRepository->getControlPanelRoleDetails($role_details['role_id']);
            $this->mainControlPanelRepository->systemAuditLogs("UPDATE", "update_role", "ROLE", "null", $get_role_details, $role_details['login_user']);

            return $get_role_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteControlPanelRole Funtion To Delete Control Panel Role
    public function deleteControlPanelRole($role_id, $login_user)
    {

        try {
            $this->controlPanelRoleRepository->deleteControlPanelRole($role_id, $login_user);
            return $this->mainControlPanelRepository->systemAuditLogs("DELETE", "delete_role", "ROLE", "null", "null", $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // assignEmployeeToRole Funtion To Assign New Employee to Role
    public function assignEmployeeToRole($role_id, $employee_number, $login_user)
    {

        try {

            $this->controlPanelRoleRepository->assignEmployeeToRole($role_id, $employee_number, $login_user);
            $get_role_details = $this->controlPanelRoleRepository->getControlPanelRoleDetails($role_id);

            $this->mainControlPanelRepository->systemAuditLogs("ASSIGN", "assign_role", "ROLE", "null", $get_role_details, $login_user);

            return $get_role_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // unassignEmployeeFromRole Funtion To Unassign Employee from Role
    public function unassignEmployeeFromRole($role_id, $employee_number, $login_user)
    {

        try {

            $this->controlPanelRoleRepository->unassignEmployeeFromRole($role_id, $employee_number, $login_user);
            $get_role_details = $this->controlPanelRoleRepository->getControlPanelRoleDetails($role_id);

            $this->mainControlPanelRepository->systemAuditLogs("UNASSIGN", "unassign_role", "ROLE", "null", $get_role_details, $login_user);

            return $get_role_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

