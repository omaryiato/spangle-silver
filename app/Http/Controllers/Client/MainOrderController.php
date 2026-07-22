<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Services\Client\MainOrderService;
use Exception;
use Illuminate\Http\Request;

class MainOrderController extends Controller
{
    public function __construct(
        protected MainOrderService $mainOrderService
    ) {}

    public function getUserOrders(User $user)
    {

        $orders_list = $this->mainOrderService->getUserOrders($user->id);

        return ResponseHelper::success(
            OrderResource::collection($orders_list),
            [
                'en' => trans('validation.get_user_orders', [], 'en'),
                'ar' => trans('validation.get_user_orders', [], 'ar'),
            ],
            200
        );
    }

    public function addNewOrder(Request $request)
    {
        try{

            $order_details = $this->mainOrderService->addNewOrder($request->validated());

            return ResponseHelper::success(
                new OrderResource($order_details),
                [
                    'en' => trans('validation.add_new_order', [], 'en'),
                    'ar' => trans('validation.add_new_order', [], 'ar'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);

        }
    }



}
