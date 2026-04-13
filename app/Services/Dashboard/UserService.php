<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\UserRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class UserService
{

    protected $userRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    UserRepository $userRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->userRepository = $userRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        try {
            return  $this->userRepository->getUsersList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails($user_id)
    {

        try {

            $user_details =  $this->userRepository->getUserDetails($user_id);

            return $user_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewUser Funtion To Add new User
    public function addNewUser($user_details)
    {

        try {

            $user_id = $this->userRepository->addNewUser($user_details);
            $get_user_details = $this->userRepository->getUserDetails($user_id);

            return $get_user_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateUser Funtion To Update User info
    public function updateUser($user_details)
    {

        try {
            $this->userRepository->updateUser($user_details);
            $get_user_details = $this->userRepository->getUserDetails($user_details['user_id']);

            return $get_user_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteUser Funtion To Delete User
    public function deleteUser($user_id, $login_user)
    {
        try {

            return $this->userRepository->deleteUser($user_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

