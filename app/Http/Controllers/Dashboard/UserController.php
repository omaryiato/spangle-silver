<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\UserService;
use App\Http\Resources\UserResource;
use App\Helpers\ResponseHelper;
use App\Models\User;

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
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);
    }

    //  Funtion to Get User Details
    public function show(User $user)
    {

        $user_details =  $this->userService->getUserDetails($user->id);

        if (!$user_details) {
            return ResponseHelper::error(
                $user_details,
                [
                    'en' => trans('validation.data_not_found'),
                    'ar' => trans('validation.data_not_found'),
                ],
                404);
        }

        return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);

    }

    //  Funtion To Add New User
    public function store(Request $request)
    {
        try{

            $user_details = $this->userService->addNewUser($request->all());
            return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);

        } catch(\Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }

    }

    //  Funtion To Update User
    public function update(Request $request, User $user)
    {
        try {

            $user_details =  $this->userService->updateUser($request->all(), $user->id);

            if (!$user_details) {
                return ResponseHelper::error(
                    new UserResource($user_details),
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                $user_details,
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);

        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, User $user)
    {
        try {

            $user_details = $this->userService->deleteUser($request->all(), $user->id);

            if (!$user_details) {
                return ResponseHelper::error(
                    $user_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                    null,
                    [
                        'en' => trans('validation.data_deleted'),
                        'ar' => trans('validation.data_deleted'),
                    ],
                    200);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
