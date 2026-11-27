<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AddNewOrder;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Client\MainOrderService;

class MainOrderController extends Controller
{
    public function __construct(
        protected MainOrderService $mainOrderService
    ) {}

    public function getUserOrders()
    {

        $orders_list = $this->mainOrderService->getUserOrders();

        return ResponseHelper::success(
            OrderResource::collection($orders_list),
            [
                'en' => trans('validation.get_user_orders', [], 'en'),
                'ar' => trans('validation.get_user_orders', [], 'ar'),
            ],
            200
        );
    }

    public function addNewOrder(AddNewOrder $request)
    {

        $order_details = $this->mainOrderService->addNewOrder($request->validated());

        return ResponseHelper::success(
            new OrderResource($order_details),
            [
                'en' => trans('validation.add_new_order', [], 'en'),
                'ar' => trans('validation.add_new_order', [], 'ar'),
            ],
            200
        );

    }

    public function cancelOrder(Order $order)
    {
        return ResponseHelper::success(OrderResource::make($this->mainOrderService->cancelOrder($order)), ['ar' => 'تم إلغاء الطلب', 'en' => 'Order canceled']);
    }
}
