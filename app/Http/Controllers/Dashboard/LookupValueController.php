<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\LookupValueService;
use App\Http\Resources\Dashboard\LookupValueResource;
use App\Helpers\ResponsHelper;

class LookupValueController extends Controller
{

    protected $lookupValueService;

    public function __construct(LookupValueService $lookupValueService)
    {
        $this->lookupValueService = $lookupValueService;
    }

    // getLookupValueList Funtion to Get Lookup Value List
    public function index()
    {

        // $validator = Validator::make($request->all(), [
        //     'lookup_type_id'       => 'bail|required|integer|exists:lookup_types,id',
        // ],
        // [
        //     'lookup_type_id.required'      => trans('ValidationTranslation.lookup_type_id_required'),
        //     'lookup_type_id.exists'      =>  trans('ValidationTranslation.lookup_type_id_exists'),
        //     'lookup_type_id.integer'      =>  trans('ValidationTranslation.lookup_type_id_integer'),
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

        $lookup_value_list = $this->lookupValueService->getLookupValueList();

        return ResponsHelper::success(LookupValueResource::collection($lookup_value_list), "Lookup Value list Returned Successfully.", 200);

    }

    // getLookupValueDetails Funtion to Get Lookup Value Details
    public function show($id)
    {
        // $validator = Validator::make($request->all(), [
        //     'lookup_value_id'       => 'bail|required|integer|exists:lookup_values,id',
        // ],
        // [
        //     'lookup_value_id.required'      => trans('ValidationTranslation.lookup_value_id_required'),
        //     'lookup_value_id.exists'      =>  trans('ValidationTranslation.lookup_value_id_exists'),
        //     'lookup_value_id.integer'      =>  trans('ValidationTranslation.lookup_value_id_integer'),
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

        $lookup_value_details =  $this->lookupValueService->getLookupValueDetails($id);

        return ResponsHelper::success(
                new LookupValueResource($lookup_value_details),
                "Lookup Value #($id) Returned Successfully.",
                200);
    }

    // addNewLookupValue Funtion To Add New Lookup Value
    public function store(Request $request)
    {

        try {
            // $validator = Validator::make($request->all(), [
            //     'lookup_type_id'       => 'bail|required|integer|exists:lookup_types,id',
            //     'lookup_value_code'       => 'bail|required|unique:lookup_values,code',
            //     'lookup_value_meaning'       => 'bail|required',
            //     'lookup_value_status'       => 'bail|integer|in:1,0',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'lookup_type_id.required'      => trans('ValidationTranslation.lookup_type_id_required'),
            //     'lookup_type_id.exists'      =>  trans('ValidationTranslation.lookup_type_id_exists'),
            //     'lookup_type_id.integer'      =>  trans('ValidationTranslation.lookup_type_id_integer'),
            //     'lookup_value_code.required'      => trans('ValidationTranslation.lookup_value_code_required'),
            //     'lookup_value_code.unique'      => trans('ValidationTranslation.lookup_value_code_unique'),
            //     'lookup_value_meaning.required'      => trans('ValidationTranslation.lookup_value_meaning_required'),
            //     'lookup_value_meaning.unique'      => trans('ValidationTranslation.lookup_value_meaning_unique'),
            //     'lookup_value_status.integer'      => trans('ValidationTranslation.lookup_value_status_integer'),
            //     'lookup_value_status.in'      => trans('ValidationTranslation.lookup_value_status_in'),
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

            $get_lookup_value_details = $this->lookupValueService->addNewLookupValue($request->all());

            return ResponsHelper::success(
                    $get_lookup_value_details,
                    "Lookup Value Added Successfully",
                    201);
        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateLookupValue Funtion To Update Lookup Value
    public function update(Request $request, $id)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'lookup_value_id'       => 'bail|required|integer|exists:lookup_values,id',
            //     'lookup_value_code'       => 'bail|required|unique:lookup_values,code,' . $request->lookup_value_id . ',id',
            //     'lookup_value_meaning'       => 'bail|required',
            //     'lookup_value_status'       => 'bail|integer|in:1,0',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'lookup_value_id.required'      => trans('ValidationTranslation.lookup_value_id_required'),
            //     'lookup_value_id.exists'      =>  trans('ValidationTranslation.lookup_value_id_exists'),
            //     'lookup_value_id.integer'      =>  trans('ValidationTranslation.lookup_value_id_integer'),
            //     'lookup_value_code.required'      => trans('ValidationTranslation.lookup_value_code_required'),
            //     'lookup_value_code.unique'      => trans('ValidationTranslation.lookup_value_code_unique'),
            //     'lookup_value_meaning.required'      => trans('ValidationTranslation.lookup_value_meaning_required'),
            //     'lookup_value_meaning.unique'      => trans('ValidationTranslation.lookup_value_meaning_unique'),
            //     'lookup_value_status.integer'      => trans('ValidationTranslation.lookup_value_status_integer'),
            //     'lookup_value_status.in'      => trans('ValidationTranslation.lookup_value_status_in'),
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

            $lookup_value_details =  $this->lookupValueService->updateLookupValue($request->all(), $id);

            return ResponsHelper::success(
                $lookup_value_details,
                "Lookup Value Updated Successfully",
                201);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function destroy(Request $request, $id)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'lookup_value_id'       => 'bail|required|integer|exists:lookup_values,id',
            //     'login_user'       => 'bail|required|integer',
            // ],
            // [
            //     'lookup_value_id.required'      => trans('ValidationTranslation.lookup_value_id_required'),
            //     'lookup_value_id.exists'      =>  trans('ValidationTranslation.lookup_value_id_exists'),
            //     'lookup_value_id.integer'      =>  trans('ValidationTranslation.lookup_value_id_integer'),
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

            $this->lookupValueService->deleteLookupValue($request->all(), $id);

            return ResponsHelper::success(
                null,
                "Lookup Value Deleted Successfully.",
                200);
        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
