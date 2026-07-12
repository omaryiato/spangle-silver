<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\UserService;
use App\Http\Resources\UserResource;
use App\Helpers\ResponseHelper;


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
                "User Returned Successfully.",
                200);
    }

    //  Funtion to Get User Details
    public function show(int $id)
    {

        $user_details =  $this->userService->getUserDetails($id);

        return ResponseHelper::success(
                new UserResource($user_details),
                "User #($id) Returned Successfully.",
                200);

    }

    //  Funtion To Add New User
    public function store(Request $request)
    {
        try{

            $user_details = $this->userService->addNewUser($request->all());
            return ResponseHelper::success($user_details,"User Added Successfully.",201);

        } catch(\Exception $exception){
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }

    }

    //  Funtion To Update User
    public function update(Request $request, int $id)
    {
        try {

            $user_details =  $this->userService->updateUser($request->all(), $id);

            return ResponseHelper::success($user_details,"User Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, int $id)
    {
        try {

            $this->userService->deleteUser($request->all(), $id);

            return ResponseHelper::success(
                    null,
                    "User Deleted Successfully.",
                    200);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
