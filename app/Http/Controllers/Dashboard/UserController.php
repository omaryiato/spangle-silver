<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\UserService;
use App\Http\Resources\Dashboard\UserResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // getUsersList Funtion to Get Users List
    public function getUsersList()
    {
        try {

            $users_list = $this->userService->getUsersList();

            return ResponsHelper::success(UserResource::collection($users_list), "User Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getUserDetails Funtion to Get User Details
    public function getUserDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'       => 'bail|required|integer|exists:users,id',
            ],
            [
                'user_id.required'      => trans('ValidationTranslation.user_id_required'),
                'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
                'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $user_id =  $request->user_id;

            $user_details =  $this->userService->getUserDetails($user_id);

            return ResponsHelper::success(new UserResource($user_details), "User #($user_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewUser Funtion To Add New User
    public function addNewUser(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'full_name'       => 'bail|required',
                'user_name'       => 'bail|required|unique:users,user_name',
                'email_address'       => 'bail|required|unique:users,email_address',
                'password'       => 'bail|required',
                'user_status'       => 'bail|integer|in:1,0',
                'user_type'       => 'bail|integer|in:2,1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'full_name.required'      => trans('ValidationTranslation.full_name_required'),
                'user_name.required'      => trans('ValidationTranslation.user_name_required'),
                'user_name.unique'      => trans('ValidationTranslation.user_name_unique'),
                'email_address.required'      => trans('ValidationTranslation.email_address_required'),
                'email_address.unique'      => trans('ValidationTranslation.email_address_unique'),
                'user_status.integer'      => trans('ValidationTranslation.user_status_integer'),
                'user_status.in'      => trans('ValidationTranslation.user_status_in'),
                'user_type.integer'      => trans('ValidationTranslation.user_type_integer'),
                'user_type.in'      => trans('ValidationTranslation.user_type_in'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $user_details=[
                "full_name" =>$request->full_name,
                "user_name" =>$request->user_name,
                "email_address" =>$request->email_address,
                "password" =>$request->password,
                "phone_number" =>$request->phone_number ?? null,
                "user_status" =>$request->user_status ?? 1,
                "user_type" =>$request->user_type ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_user_details = $this->userService->addNewUser($user_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_user', [
                    'full_name' => $request->full_name
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_user', [
                    'full_name' => $request->full_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_user_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateUser Funtion To Update User
    public function updateUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'       => 'bail|required|integer|exists:users,id',
                'full_name'       => 'bail|required',
                'user_name'       => 'bail|required|unique:users,user_name',
                'email_address'       => 'bail|required|unique:users,email_address',
                'password'       => 'bail|required',
                'user_status'       => 'bail|integer|in:1,0',
                'user_type'       => 'bail|integer|in:2,1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'user_id.required'      => trans('ValidationTranslation.user_id_required'),
                'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
                'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),
                'full_name.required'      => trans('ValidationTranslation.full_name_required'),
                'user_name.required'      => trans('ValidationTranslation.user_name_required'),
                'user_name.unique'      => trans('ValidationTranslation.user_name_unique'),
                'email_address.required'      => trans('ValidationTranslation.email_address_required'),
                'email_address.unique'      => trans('ValidationTranslation.email_address_unique'),
                'user_status.integer'      => trans('ValidationTranslation.user_status_integer'),
                'user_status.in'      => trans('ValidationTranslation.user_status_in'),
                'user_type.integer'      => trans('ValidationTranslation.user_type_integer'),
                'user_type.in'      => trans('ValidationTranslation.user_type_in'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $user_details=[
                "user_id" =>$request->user_id,
                "full_name" =>$request->full_name,
                "user_name" =>$request->user_name,
                "email_address" =>$request->email_address,
                "password" =>$request->password,
                "phone_number" =>$request->phone_number ?? null,
                "user_status" =>$request->user_status ?? 1,
                "user_type" =>$request->user_type ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_user_details =  $this->userService->updateUser($user_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_user', [
                    'full_name' => $request->full_name
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_user', [
                    'full_name' => $request->full_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_user_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteUser Funtion To Delete User
    public function deleteUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'       => 'bail|required|integer|exists:users,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'user_id.required'      => trans('ValidationTranslation.user_id_required'),
                'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
                'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $user_id =  $request->user_id;
            $login_user =  $request->login_user;

            $this->userService->deleteUser($user_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_user', [
                    'user_id' => $user_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_user', [
                    'user_id' => $user_id
                ], 'ar'),
            ];

            return ResponsHelper::success($user_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
