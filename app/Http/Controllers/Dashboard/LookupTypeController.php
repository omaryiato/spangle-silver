<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\LookupTypeService;
use App\Http\Resources\Dashboard\LookupTypeResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LookupTypeController extends Controller
{

    protected $lookupTypeService;

    public function __construct(LookupTypeService $lookupTypeService)
    {
        $this->lookupTypeService = $lookupTypeService;
    }

    // getLookupTypeList Funtion to Get Lookup Type List
    public function getLookupTypeList()
    {
        try {

            $lookup_type_list = $this->lookupTypeService->getLookupTypeList();

            return ResponsHelper::success(LookupTypeResource::collection($lookup_type_list), "Lookup Type Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getLookupTypeDetails Funtion to Get Lookup Type Details
    public function getLookupTypeDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'lookup_type_id'       => 'bail|required|integer|exists:lookup_types,id',
            ],
            [
                'lookup_type_id.required'      => trans('ValidationTranslation.lookup_type_id_required'),
                'lookup_type_id.exists'      =>  trans('ValidationTranslation.lookup_type_id_exists'),
                'lookup_type_id.integer'      =>  trans('ValidationTranslation.lookup_type_id_integer'),
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

            $lookup_type_id =  $request->lookup_type_id;

            $lookup_type_details =  $this->lookupTypeService->getLookupTypeDetails($lookup_type_id);

            return ResponsHelper::success(new LookupTypeResource($lookup_type_details), "Lookup_type #($lookup_type_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewLookupType Funtion To Add New Lookup Type
    public function addNewLookupType(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'lookup_type_en_name'       => 'bail|required|unique:lookup_types,type_en_name',
                'lookup_type_ar_name'       => 'bail|required|unique:lookup_types,type_ar_name',
                'lookup_type_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'lookup_type_en_name.required'      => trans('ValidationTranslation.lookup_type_en_name_required'),
                'lookup_type_en_name.unique'      => trans('ValidationTranslation.lookup_type_en_name_unique'),
                'lookup_type_ar_name.required'      => trans('ValidationTranslation.lookup_type_ar_name_required'),
                'lookup_type_ar_name.unique'      => trans('ValidationTranslation.lookup_type_ar_name_unique'),
                'lookup_type_status.integer'      => trans('ValidationTranslation.lookup_type_status_integer'),
                'lookup_type_status.in'      => trans('ValidationTranslation.lookup_type_status_in'),
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

            $lookup_type_details=[
                "lookup_type_en_name" =>$request->lookup_type_en_name,
                "lookup_type_ar_name" =>$request->lookup_type_ar_name,
                "lookup_type_description" =>$request->lookup_type_description ?? null,
                "lookup_type_status" =>$request->lookup_type_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_lookup_type_details = $this->lookupTypeService->addNewLookupType($lookup_type_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_lookup_type', [
                    'lookup_type_en_name' => $request->lookup_type_en_name
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_lookup_type', [
                    'lookup_type_ar_name' => $request->lookup_type_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_lookup_type_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateLookupType Funtion To Update Lookup Type
    public function updateLookupType(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'lookup_type_id'       => 'bail|required|integer|exists:lookup_types,id',
                'lookup_type_en_name'       => 'bail|required|unique:lookup_types,type_en_name,' . $request->lookup_type_id . ',id',
                'lookup_type_ar_name'       => 'bail|required|unique:lookup_types,type_ar_name,' . $request->lookup_type_id . ',id',
                'lookup_type_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'lookup_type_id.required'      => trans('ValidationTranslation.lookup_type_id_required'),
                'lookup_type_id.exists'      =>  trans('ValidationTranslation.lookup_type_id_exists'),
                'lookup_type_id.integer'      =>  trans('ValidationTranslation.lookup_type_id_integer'),
                'lookup_type_en_name.required'      => trans('ValidationTranslation.lookup_type_en_name_required'),
                'lookup_type_en_name.unique'      => trans('ValidationTranslation.lookup_type_en_name_unique'),
                'lookup_type_ar_name.required'      => trans('ValidationTranslation.lookup_type_ar_name_required'),
                'lookup_type_ar_name.unique'      => trans('ValidationTranslation.lookup_type_ar_name_unique'),
                'lookup_type_status.integer'      => trans('ValidationTranslation.lookup_type_status_integer'),
                'lookup_type_status.in'      => trans('ValidationTranslation.lookup_type_status_in'),
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

            $lookup_type_details=[
                "lookup_type_id" =>$request->lookup_type_id,
                "lookup_type_en_name" =>$request->lookup_type_en_name,
                "lookup_type_ar_name" =>$request->lookup_type_ar_name,
                "lookup_type_description" =>$request->lookup_type_description ?? null,
                "lookup_type_status" =>$request->lookup_type_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_lookup_type_details =  $this->lookupTypeService->updateLookupType($lookup_type_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_lookup_type', [
                    'lookup_type_en_name' => $request->lookup_type_en_name
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_lookup_type', [
                    'lookup_type_ar_name' => $request->lookup_type_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_lookup_type_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteLookupType Funtion To Delete Lookup Type
    public function deleteLookupType(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'lookup_type_id'       => 'bail|required|integer|exists:lookup_types,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'lookup_type_id.required'      => trans('ValidationTranslation.lookup_type_id_required'),
                'lookup_type_id.exists'      =>  trans('ValidationTranslation.lookup_type_id_exists'),
                'lookup_type_id.integer'      =>  trans('ValidationTranslation.lookup_type_id_integer'),
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

            $lookup_type_id =  $request->lookup_type_id;
            $login_user =  $request->login_user;

            $this->lookupTypeService->deleteLookupType($lookup_type_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_lookup_type', [
                    'lookup_type_id' => $lookup_type_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_lookup_type', [
                    'lookup_type_id' => $lookup_type_id
                ], 'ar'),
            ];

            return ResponsHelper::success($lookup_type_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
