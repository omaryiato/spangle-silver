<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\UserService;
use App\Http\Resources\Dashboard\UserResource;
use App\Helpers\ResponsHelper;


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
        return ResponsHelper::success(
                UserResource::collection($users_list),
                "User Returned Successfully.",
                200);
    }

    //  Funtion to Get User Details
    public function show($id)
    {

        // $validator = Validator::make($request->all(), [
        //     'user_id'       => 'bail|required|integer|exists:users,id',
        // ],
        // [
        //     'user_id.required'      => trans('ValidationTranslation.user_id_required'),
        //     'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
        //     'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),
        // ]);

        // if ($validator->fails()) {

        //     $field = $validator->errors()->keys()[0];

        //     $failedRules = $validator->failed()[$field];
        //     $rule = strtolower(array_key_first($failedRules));

        //     $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

        //     $response_message = [
        //         'en' => trans($translation_key, [], 'en'),
        //         'ar' => trans($translation_key, [], 'ar'),
        //     ];

        //     return ResponsHelper::error($request->all(),$response_message,400);
        // }

        $user_details =  $this->userService->getUserDetails($id);

        return ResponsHelper::success(
                new UserResource($user_details),
                "User #($id) Returned Successfully.",
                200);

    }

    //  Funtion To Add New User
    public function store(Request $request)
    {
        try{

            $user_details = $this->userService->addNewUser($request->all());
            return ResponsHelper::success($user_details,"User Added Successfully.",201);

        } catch(\Exception $exception){
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }

    }

    //  Funtion To Update User
    public function update(Request $request, $id)
    {
        try {

            $user_details =  $this->userService->updateUser($request->all(), $id);

            return ResponsHelper::success($user_details,"User Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, $id)
    {
        try {

            $this->userService->deleteUser($request->all(), $id);

            return ResponsHelper::success(
                    null,
                    "User Deleted Successfully.",
                    200);
        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
