<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\AddUser;
use App\Http\Requests\Dashboard\User\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Dashboard\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Funtion to Get Users List
    public function index()
    {
        $users_list = $this->userService->getUsersList();

        return ResponseHelper::success(
            UserResource::collection($users_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    //  Funtion to Get User Details
    public function show(User $user)
    {

        $user_details = $this->userService->getUserDetails($user);

        if (! $user_details) {
            return ResponseHelper::error(
                $user_details,
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            new UserResource($user_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    //  Funtion To Add New User
    public function store(AddUser $request)
    {

        $user_details = $this->userService->addNewUser($request->validated());

        return ResponseHelper::success(
            new UserResource($user_details),
            [
                'en' => trans('validation.data_added', [], 'en'),
                'ar' => trans('validation.data_added', [], 'ar'),
            ],
            201);

    }

    //  Funtion To Update User
    public function update(UpdateUser $request, User $user)
    {

        $user_details = $this->userService->updateUser($request->validated(), $user);

        if (! $user_details) {
            return ResponseHelper::error(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            new UserResource($user_details),
            [
                'en' => trans('validation.data_updated', [], 'en'),
                'ar' => trans('validation.data_updated', [], 'ar'),
            ],
            200);

    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, User $user)
    {

        $user_details = $this->userService->deleteUser($user);

        if (! $user_details) {
            return ResponseHelper::error(
                $user_details,
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            null,
            [
                'en' => trans('validation.data_deleted', [], 'en'),
                'ar' => trans('validation.data_deleted', [], 'ar'),
            ],
            200);

    }
}
