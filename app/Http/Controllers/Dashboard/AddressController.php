<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\AddressService;
use App\Http\Resources\Dashboard\AddressResource;
use App\Helpers\ResponsHelper;


class AddressController extends Controller
{

    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Funtion to Get Addresses List
    public function index()
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

        $addresss_list = $this->addressService->getAddressesList();

        return ResponsHelper::success(AddressResource::collection($addresss_list), "Addresses Returned Successfully.", 200);

    }

    // Funtion to Get Address Details
    public function show($id)
    {

        // $validator = Validator::make($request->all(), [
        //     'address_id'       => 'bail|required|integer|exists:addresses,id',
        // ],
        // [
        //     'address_id.required'      => trans('ValidationTranslation.address_id_required'),
        //     'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
        //     'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),
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

        $address_details =  $this->addressService->getAddressDetails($id);

        return ResponsHelper::success(new AddressResource($address_details), "Address #($id) Returned Successfully.", 200);

    }

    // Funtion To Add New Address
    public function store(Request $request)
    {

        try {
            // $validator = Validator::make($request->all(), [
            //     'user_id'       => 'bail|required|integer|exists:users,id',
            //     'address_line'       => 'bail|required',
            //     'city'       => 'bail|required',
            //     'country'       => 'bail|required',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'user_id.required'      => trans('ValidationTranslation.user_id_required'),
            //     'user_id.exists'      =>  trans('ValidationTranslation.user_id_exists'),
            //     'user_id.integer'      =>  trans('ValidationTranslation.user_id_integer'),

            //     'address_line.required'      => trans('ValidationTranslation.address_line_required'),
            //     'city.required'      => trans('ValidationTranslation.city_required'),
            //     'country.required'      => trans('ValidationTranslation.country_required'),
            //     'login_user.required'      => trans('ValidationTranslation.login_user_required'),
            //     'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
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

            $address_details = $this->addressService->addNewAddress($request->all());

            return ResponsHelper::success($address_details,"User Address Add Successfully.",201);
        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Update Address
    public function update(Request $request, $id)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'address_id'       => 'bail|required|integer|exists:addresses,id',
            //     'address_line'       => 'bail|required',
            //     'city'       => 'bail|required',
            //     'country'       => 'bail|required',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'address_id.required'      => trans('ValidationTranslation.address_id_required'),
            //     'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
            //     'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),

            //     'address_line.required'      => trans('ValidationTranslation.address_line_required'),
            //     'city.required'      => trans('ValidationTranslation.city_required'),
            //     'country.required'      => trans('ValidationTranslation.country_required'),
            //     'login_user.required'      => trans('ValidationTranslation.login_user_required'),
            //     'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
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

            $address_details =  $this->addressService->updateAddress($request->all(), $id);

            return ResponsHelper::success($address_details,"User Address Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Delete Address
    public function destroy(Request $request, $id)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'address_id'       => 'bail|required|integer|exists:addresses,id',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'address_id.required'      => trans('ValidationTranslation.address_id_required'),
            //     'address_id.exists'      =>  trans('ValidationTranslation.address_id_exists'),
            //     'address_id.integer'      =>  trans('ValidationTranslation.address_id_integer'),
            //     'login_user.required'      => trans('ValidationTranslation.login_user_required'),
            //     'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
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

            $this->addressService->deleteAddress($request->all(), $id);

            return ResponsHelper::success(null, "User Address Deleted Successfully.", 201);
        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
