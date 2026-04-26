<?php

namespace App\Repositories\Dashboard;

use App\Models\User;



class UserRepository
{

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return User::with('addresses')->get();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails($id)
    {
        return User::with("addresses")->findOrFail($id);
    }

    // addNewUser Funtion To Add new User
    public function addNewUser($user_details)
    {
        return User::create($user_details);
    }

    // updateUser Funtion To Update User info
    public function updateUser(User $user, $user_request)
    {
        $user->update($user_request);
        return $user;
    }

    // deleteUser Funtion To Delete User
    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}

