<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\CouponService;
use App\Http\Resources\Dashboard\CouponResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{

    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    // getCouponsList Funtion to Get Coupons List
    public function getCouponsList()
    {
        try {

            $coupon_list = $this->couponService->getCouponsList();

            return ResponsHelper::success(CouponResource::collection($coupon_list), "Coupon Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getCouponDetails Funtion to Get Coupon Details
    public function getCouponDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'coupon_id'       => 'bail|required|integer|exists:coupons,id',
            ],
            [
                'coupon_id.required'      => trans('ValidationTranslation.coupon_id_required'),
                'coupon_id.exists'      =>  trans('ValidationTranslation.coupon_id_exists'),
                'coupon_id.integer'      =>  trans('ValidationTranslation.coupon_id_integer'),
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

            $coupon_id =  $request->coupon_id;

            $coupon_details =  $this->couponService->getCouponDetails($coupon_id);

            return ResponsHelper::success(new CouponResource($coupon_details), "Coupon #($coupon_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewCoupon Funtion To Add New Coupon
    public function addNewCoupon(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'coupon_code'       => 'bail|required|unique:coupons,code',
                'coupon_discount_amount'       => 'bail|required|numaric',
                'coupon_minimum_order_amount'       => 'bail|required|numaric',
                'coupon_max_usage'       => 'bail|required|integer',
                'coupon_used_count'       => 'bail|required|integer',
                'coupon_expires_at'       => 'bail|required',
                'coupon_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'coupon_code.required'      => trans('ValidationTranslation.coupon_code_required'),
                'coupon_code.unique'      => trans('ValidationTranslation.coupon_code_unique'),
                'coupon_discount_amount.required'      => trans('ValidationTranslation.coupon_discount_amount_required'),
                'coupon_discount_amount.numaric'      => trans('ValidationTranslation.coupon_discount_amount_numaric'),
                'coupon_minimum_order_amount.required'      => trans('ValidationTranslation.coupon_minimum_order_amount_required'),
                'coupon_minimum_order_amount.numaric'      => trans('ValidationTranslation.coupon_minimum_order_amount_numaric'),
                'coupon_max_usage.required'      => trans('ValidationTranslation.coupon_max_usage_required'),
                'coupon_max_usage.integer'      => trans('ValidationTranslation.coupon_max_usage_integer'),
                'coupon_used_count.required'      => trans('ValidationTranslation.coupon_used_count_required'),
                'coupon_used_count.integer'      => trans('ValidationTranslation.coupon_used_count_integer'),
                'coupon_expires_at.required'      => trans('ValidationTranslation.coupon_expires_at_required'),
                'coupon_status.integer'      => trans('ValidationTranslation.coupon_status_integer'),
                'coupon_status.in'      => trans('ValidationTranslation.coupon_status_in'),
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

            $coupon_details=[
                "coupon_code" =>$request->coupon_code,
                "coupon_discount_amount" =>$request->coupon_discount_amount,
                "coupon_minimum_order_amount" =>$request->coupon_minimum_order_amount ?? null,
                "coupon_max_usage" =>$request->coupon_max_usage ?? null,
                "coupon_used_count" =>$request->coupon_used_count ?? null,
                "coupon_expires_at" =>$request->coupon_expires_at ?? null,
                "coupon_status" =>$request->coupon_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_coupon_details = $this->couponService->addNewCoupon($coupon_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_coupon', [
                    'coupon_code' => $request->coupon_code
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_coupon', [
                    'coupon_code' => $request->coupon_code
                ], 'ar'),
            ];

            return ResponsHelper::success($get_coupon_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateCoupon Funtion To Update Coupon
    public function updateCoupon(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'coupon_id'       => 'bail|required|integer|exists:coupons,id',
                'coupon_code'       => 'bail|required|unique:coupons,code',
                'coupon_discount_amount'       => 'bail|required|numaric',
                'coupon_minimum_order_amount'       => 'bail|required|numaric',
                'coupon_max_usage'       => 'bail|required|integer',
                'coupon_used_count'       => 'bail|required|integer',
                'coupon_expires_at'       => 'bail|required',
                'coupon_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'coupon_id.required'      => trans('ValidationTranslation.coupon_id_required'),
                'coupon_id.exists'      =>  trans('ValidationTranslation.coupon_id_exists'),
                'coupon_id.integer'      =>  trans('ValidationTranslation.coupon_id_integer'),
                'coupon_code.required'      => trans('ValidationTranslation.coupon_code_required'),
                'coupon_code.unique'      => trans('ValidationTranslation.coupon_code_unique'),
                'coupon_discount_amount.required'      => trans('ValidationTranslation.coupon_discount_amount_required'),
                'coupon_discount_amount.numaric'      => trans('ValidationTranslation.coupon_discount_amount_numaric'),
                'coupon_minimum_order_amount.required'      => trans('ValidationTranslation.coupon_minimum_order_amount_required'),
                'coupon_minimum_order_amount.numaric'      => trans('ValidationTranslation.coupon_minimum_order_amount_numaric'),
                'coupon_max_usage.required'      => trans('ValidationTranslation.coupon_max_usage_required'),
                'coupon_max_usage.integer'      => trans('ValidationTranslation.coupon_max_usage_integer'),
                'coupon_used_count.required'      => trans('ValidationTranslation.coupon_used_count_required'),
                'coupon_used_count.integer'      => trans('ValidationTranslation.coupon_used_count_integer'),
                'coupon_expires_at.required'      => trans('ValidationTranslation.coupon_expires_at_required'),
                'coupon_status.integer'      => trans('ValidationTranslation.coupon_status_integer'),
                'coupon_status.in'      => trans('ValidationTranslation.coupon_status_in'),
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

            $coupon_details=[
                "coupon_id" =>$request->coupon_id,
                "coupon_code" =>$request->coupon_code,
                "coupon_discount_amount" =>$request->coupon_discount_amount,
                "coupon_minimum_order_amount" =>$request->coupon_minimum_order_amount ?? null,
                "coupon_max_usage" =>$request->coupon_max_usage ?? null,
                "coupon_used_count" =>$request->coupon_used_count ?? null,
                "coupon_expires_at" =>$request->coupon_expires_at ?? null,
                "coupon_status" =>$request->coupon_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_coupon_details =  $this->couponService->updateCoupon($coupon_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_coupon', [
                    'coupon_code' => $request->coupon_code
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_coupon', [
                    'coupon_code' => $request->coupon_code
                ], 'ar'),
            ];

            return ResponsHelper::success($get_coupon_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'coupon_id'       => 'bail|required|integer|exists:coupons,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'coupon_id.required'      => trans('ValidationTranslation.coupon_id_required'),
                'coupon_id.exists'      =>  trans('ValidationTranslation.coupon_id_exists'),
                'coupon_id.integer'      =>  trans('ValidationTranslation.coupon_id_integer'),
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

            $coupon_id =  $request->coupon_id;
            $login_user =  $request->login_user;

            $this->couponService->deleteCoupon($coupon_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_coupon', [
                    'coupon_id' => $coupon_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_coupon', [
                    'coupon_id' => $coupon_id
                ], 'ar'),
            ];

            return ResponsHelper::success($coupon_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
