<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\OrderService;
use App\Http\Resources\Dashboard\OrderResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // getOrdersList Funtion to Get Orders List
    public function getOrdersList()
    {
        try {

            $order_list = $this->orderService->getOrdersList();

            return ResponsHelper::success(OrderResource::collection($order_list), "Orders Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getOrderDetails Funtion to Get Order Details
    public function getOrderDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'order_id'       => 'bail|required|integer|exists:orders,id',
            ],
            [
                'order_id.required'      => trans('ValidationTranslation.order_id_required'),
                'order_id.exists'      =>  trans('ValidationTranslation.order_id_exists'),
                'order_id.integer'      =>  trans('ValidationTranslation.order_id_integer'),
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

            $order_id =  $request->order_id;

            $order_details =  $this->orderService->getOrderDetails($order_id);

            return ResponsHelper::success(new OrderResource($order_details), "Order #($order_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'order_id'       => 'bail|required|integer|exists:orders,id',
                'order_status'       => 'bail|required|integer|exists:lookup_values,code',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'order_id.required'      => trans('ValidationTranslation.order_id_required'),
                'order_id.exists'      =>  trans('ValidationTranslation.order_id_exists'),
                'order_id.integer'      =>  trans('ValidationTranslation.order_id_integer'),
                'order_status.required'      => trans('ValidationTranslation.order_status_required'),
                'order_status.exists'      =>  trans('ValidationTranslation.order_status_exists'),
                'order_status.integer'      =>  trans('ValidationTranslation.order_status_integer'),
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

            $order_id = $request->order_id;
            $order_status = $request->order_status;
            $login_user = $request->login_user;

            $get_order_details = $this->orderService->confirmOrder($order_id, $order_status, $login_user);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.confirm_order', [
                    'order_id' => $request->order_id
                ], 'en'),
                'ar' => __('ValidationTranslation.confirm_order', [
                    'order_id' => $request->order_id
                ], 'ar'),
            ];

            return ResponsHelper::success($get_order_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
