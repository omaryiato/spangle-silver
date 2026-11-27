<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Repositories\Dashboard\UserRepository;
use App\Services\ActingUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository, protected ActingUserService $actingUserService)
    {
        $this->userRepository = $userRepository;
    }

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return $this->userRepository->getUsersList();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails(User $user)
    {

        return $this->userRepository->getUserDetails($user);
    }

    // addNewUser Funtion To Add new User
    public function addNewUser(array $user_request)
    {
        return $this->userRepository->addNewUser($this->prepareRequestInfo($user_request));
    }

    // updateUser Funtion To Update User info
    public function updateUser(array $user_request, User $user)
    {
        // $user_details = $this->userRepository->getUserDetails($id);
        // if(!$user_details){
        //     return null;
        // }
        $update = fn () => $this->userRepository->updateUser($user, $this->prepareRequestInfo($user_request, true));
        if (isset($user_request['password']) || (isset($user_request['status']) && (int) $user_request['status'] === 0)) {
            return DB::transaction(function () use ($update, $user) {
                $result = $update();
                $this->userRepository->revokeTokens($user);

                return $result;
            });
        }

        return $update();
    }

    // deleteUser Funtion To Delete User
    public function deleteUser(User $user)
    {
        // $user_details = $this->userRepository->getUserDetails($user);
        // if(!$user_details){
        //     return null;
        // }
        return DB::transaction(function () use ($user) {
            $result = $this->userRepository->deleteUser($user, $this->actingUserService->attributes(['status' => 0], true));
            $this->userRepository->revokeTokens($user);

            return $result;
        });
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {

        $request_data = [
            'full_name' => $request_info['full_name'] ?? null,
            'user_name' => $request_info['user_name'] ?? null,
            'phone_number' => $request_info['phone_number'] ?? null,
            'email_address' => $request_info['email_address'] ?? null,
            'password' => isset($request_info['password'])
                    ? Hash::make($request_info['password'])
                    : null,
            'status' => $request_info['status'] ?? 1,
            'user_type' => $request_info['user_type'] ?? 0,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }
}
