<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Order\ConfirmOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Dashboard\OrderService;

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
        $order_list = $this->orderService->getOrdersList();

        return ResponseHelper::success(
            OrderResource::collection($order_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    // getOrderDetails Funtion to Get Order Details
    public function getOrderDetails(Order $order)
    {
        $order_details = $this->orderService->getOrderDetails($order);

        return ResponseHelper::success(
            new OrderResource($order_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(ConfirmOrderRequest $request, Order $order)
    {

        $order_details = $this->orderService->confirmOrder($request->validated(), $order);

        if (! $order_details) {
            return ResponseHelper::error(
                $order_details,
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            new OrderResource($order_details),
            [
                'en' => trans('validation.data_updated', [], 'en'),
                'ar' => trans('validation.data_updated', [], 'ar'),
            ],
            200);

    }
}
