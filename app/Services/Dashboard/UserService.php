<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\UserRepository;



class UserService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return  $this->userRepository->getUsersList();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails($id)
    {

        return  $this->userRepository->getUserDetails($id);
    }

    // addNewUser Funtion To Add new User
    public function addNewUser($user_request)
    {
        return $this->userRepository->addNewUser($user_request);
    }

    // updateUser Funtion To Update User info
    public function updateUser($user_request, $id)
    {
        $user_details = $this->userRepository->getUserDetails($id);
        return $this->userRepository->updateUser($user_details, $user_request);
    }

    // deleteUser Funtion To Delete User
    public function deleteUser($user_request, $id)
    {
        $user_details = $this->userRepository->getUserDetails($id);
        return $this->userRepository->deleteUser($user_details);
    }

}

