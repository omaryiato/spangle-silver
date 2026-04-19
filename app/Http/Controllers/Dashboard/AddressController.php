<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\AddressService;
use App\Http\Resources\Dashboard\AddressResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{

    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // getAddressesList Funtion to Get Addresses List
    public function getAddressesList(Request $request)
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

            $addresss_list = $this->addressService->getAddressesList($user_id);

            return ResponsHelper::success(AddressResource::collection($addresss_list), "Addresses Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getAddressDetails Funtion to Get Address Details
    public function getAddressDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'address_id'       => 'bail|required|integer|exists:addresses,id',
            ],
            [
                'address_id.required'      => trans('ValidationTranslation.address_id_required'),
                'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
                'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),
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

            $address_id =  $request->address_id;

            $address_details =  $this->addressService->getAddressDetails($address_id);

            return ResponsHelper::success(new AddressResource($address_details), "Address #($address_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewAddress Funtion To Add New Address
    public function addNewAddress(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'user_id'       => 'bail|required|integer|exists:users,id',
                'address_line'       => 'bail|required',
                'city'       => 'bail|required',
                'country'       => 'bail|required',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'user_id.required'      => trans('ValidationTranslation.user_id_required'),
                'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
                'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),

                'address_line.required'      => trans('ValidationTranslation.address_line_required'),
                'city.required'      => trans('ValidationTranslation.city_required'),
                'country.required'      => trans('ValidationTranslation.country_required'),
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

            $address_details=[
                "user_id" =>$request->user_id,
                "address_line" =>$request->address_line,
                "city" =>$request->city,
                "country" =>$request->country,
                "full_name" =>$request->full_name,
                "label" =>$request->label,
                "postal_code" =>$request->postal_code,
                "phone" =>$request->phone,
                "is_default" =>$request->is_default ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_address_details = $this->addressService->addNewAddress($address_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_address', [
                    'address_line' => $request->address_line
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_address', [
                    'address_line' => $request->address_line
                ], 'ar'),
            ];

            return ResponsHelper::success($get_address_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateAddress Funtion To Update Address
    public function updateAddress(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'address_id'       => 'bail|required|integer|exists:addresses,id',
                'address_line'       => 'bail|required',
                'city'       => 'bail|required',
                'country'       => 'bail|required',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'address_id.required'      => trans('ValidationTranslation.address_id_required'),
                'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
                'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),

                'address_line.required'      => trans('ValidationTranslation.address_line_required'),
                'city.required'      => trans('ValidationTranslation.city_required'),
                'country.required'      => trans('ValidationTranslation.country_required'),
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

            $address_details=[
                "address_id" =>$request->address_id,
                "address_line" =>$request->address_line,
                "city" =>$request->city,
                "country" =>$request->country,
                "full_name" =>$request->full_name,
                "label" =>$request->label,
                "postal_code" =>$request->postal_code,
                "phone" =>$request->phone,
                "is_default" =>$request->is_default ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_address_details =  $this->addressService->updateAddress($address_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_address', [
                    'address_line' => $request->address_line
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_address', [
                    'address_line' => $request->address_line
                ], 'ar'),
            ];

            return ResponsHelper::success($get_address_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteAddress Funtion To Delete Address
    public function deleteAddress(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'address_id'       => 'bail|required|integer|exists:addresses,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'address_id.required'      => trans('ValidationTranslation.address_id_required'),
                'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
                'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),
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

            $address_id =  $request->address_id;
            $login_user =  $request->login_user;

            $this->addressService->deleteAddress($address_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_address', [
                    'address_id' => $address_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_address', [
                    'address_id' => $address_id
                ], 'ar'),
            ];

            return ResponsHelper::success($address_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
