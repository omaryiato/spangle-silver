<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\ShippingMethodService;
use App\Http\Resources\Dashboard\ShippingMethodResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShippingMethodController extends Controller
{

    protected $shippingMethodService;

    public function __construct(ShippingMethodService $shippingMethodService)
    {
        $this->shippingMethodService = $shippingMethodService;
    }

    // getShippingMethodsList Funtion to Get Shipping Methods List
    public function getShippingMethodsList()
    {
        try {

            $shipping_methods_list = $this->shippingMethodService->getShippingMethodsList();

            return ResponsHelper::success(ShippingMethodResource::collection($shipping_methods_list), "Shipping Method Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getShippingMethodDetails Funtion to Get Shipping Method Details
    public function getShippingMethodDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'shipping_method_id'       => 'bail|required|integer|exists:shipping_methods,id',
            ],
            [
                'shipping_method_id.required'      => trans('ValidationTranslation.shipping_method_id_required'),
                'shipping_method_id.exists'      =>  trans('ValidationTranslation.shipping_method_id_exists'),
                'shipping_method_id.integer'      =>  trans('ValidationTranslation.shipping_method_id_integer'),
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

            $shipping_method_id =  $request->shipping_method_id;

            $shipping_method_details =  $this->shippingMethodService->getShippingMethodDetails($shipping_method_id);

            return ResponsHelper::success(new ShippingMethodResource($shipping_method_details), "Shipping Method #($shipping_method_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewShippingMethod Funtion To Add New Shipping Method
    public function addNewShippingMethod(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'method_en_name'       => 'bail|required|unique:shipping_methods,method_en_name',
                'method_ar_name'       => 'bail|required|unique:shipping_methods,method_ar_name',
                'method_price'       => 'bail|required|numaric',
                'method_estimated_days'       => 'bail|required|integer',
                'method_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'method_en_name.required'      => trans('ValidationTranslation.method_en_name_required'),
                'method_en_name.unique'      => trans('ValidationTranslation.method_en_name_unique'),
                'method_ar_name.required'      => trans('ValidationTranslation.method_ar_name_required'),
                'method_ar_name.unique'      => trans('ValidationTranslation.method_ar_name_unique'),
                'method_status.integer'      => trans('ValidationTranslation.method_status_integer'),
                'method_status.in'      => trans('ValidationTranslation.method_status_in'),
                'method_estimated_days.required'      => trans('ValidationTranslation.method_estimated_days_required'),
                'method_estimated_days.integer'      => trans('ValidationTranslation.method_estimated_days_integer'),
                'method_price.required'      => trans('ValidationTranslation.method_price_required'),
                'method_price.numaric'      => trans('ValidationTranslation.method_price_integer'),
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

            $shipping_method_details=[
                "method_en_name" =>$request->method_en_name,
                "method_ar_name" =>$request->method_ar_name,
                "method_price" =>$request->method_price ?? null,
                "method_estimated_days" =>$request->method_estimated_days ?? null,
                "method_status" =>$request->method_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_shipping_method_details = $this->shippingMethodService->addNewShippingMethod($shipping_method_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_shipping_method', [
                    ' ' => $request->method_en_name
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_shipping_method', [
                    'method_ar_name' => $request->method_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_shipping_method_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateShippingMethod Funtion To Update Shipping Method
    public function updateShippingMethod(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'shipping_method_id'       => 'bail|required|integer|exists:shipping_methods,id',
                'method_en_name'       => 'bail|required|unique:shipping_methods,method_en_name,' . $request->shipping_method_id . ',id',
                'method_ar_name'       => 'bail|required|unique:shipping_methods,method_ar_name,' . $request->shipping_method_id . ',id',
                'method_price'       => 'bail|required|numaric',
                'method_estimated_days'       => 'bail|required|integer',
                'method_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'shipping_method_id.required'      => trans('ValidationTranslation.shipping_method_id_required'),
                'shipping_method_id.exists'      =>  trans('ValidationTranslation.shipping_method_id_exists'),
                'shipping_method_id.integer'      =>  trans('ValidationTranslation.shipping_method_id_integer'),
                'method_en_name.required'      => trans('ValidationTranslation.method_en_name_required'),
                'method_en_name.unique'      => trans('ValidationTranslation.method_en_name_unique'),
                'method_ar_name.required'      => trans('ValidationTranslation.method_ar_name_required'),
                'method_ar_name.unique'      => trans('ValidationTranslation.method_ar_name_unique'),
                'method_status.integer'      => trans('ValidationTranslation.method_status_integer'),
                'method_status.in'      => trans('ValidationTranslation.method_status_in'),
                'method_estimated_days.required'      => trans('ValidationTranslation.method_estimated_days_required'),
                'method_estimated_days.integer'      => trans('ValidationTranslation.method_estimated_days_integer'),
                'method_price.required'      => trans('ValidationTranslation.method_price_required'),
                'method_price.numaric'      => trans('ValidationTranslation.method_price_integer'),
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

            $shipping_method_details=[
                "shipping_method_id" =>$request->shipping_method_id,
                "method_en_name" =>$request->method_en_name,
                "method_ar_name" =>$request->method_ar_name,
                "method_price" =>$request->method_price,
                "method_estimated_days" =>$request->method_estimated_days,
                "method_status" =>$request->method_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_shipping_method_details =  $this->shippingMethodService->updateShippingMethod($shipping_method_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_shipping_method', [
                    'method_en_name' => $request->method_en_name
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_shipping_method', [
                    'method_ar_name' => $request->method_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_shipping_method_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'shipping_method_id'       => 'bail|required|integer|exists:shipping_methods,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'shipping_method_id.required'      => trans('ValidationTranslation.shipping_method_id_required'),
                'shipping_method_id.exists'      =>  trans('ValidationTranslation.shipping_method_id_exists'),
                'shipping_method_id.integer'      =>  trans('ValidationTranslation.shipping_method_id_integer'),
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

            $shipping_method_id =  $request->shipping_method_id;
            $login_user =  $request->login_user;

            $this->shippingMethodService->deleteShippingMethod($shipping_method_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_shipping_method', [
                    'shipping_method_id' => $shipping_method_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_shipping_method', [
                    'shipping_method_id' => $shipping_method_id
                ], 'ar'),
            ];

            return ResponsHelper::success($shipping_method_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
