<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\OrderService;
use App\Http\Resources\OrderResource;
use App\Helpers\ResponseHelper;
use App\Models\Order;
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
        $order_list = $this->orderService->getOrdersList();

        return ResponseHelper::success(
            OrderResource::collection($order_list),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    // getOrderDetails Funtion to Get Order Details
    public function getOrderDetails(Order $order)
    {
        $order_details =  $this->orderService->getOrderDetails($order);

        return ResponseHelper::success(
            new OrderResource($order_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(Request $request, Order $order)
    {

        try {

            $order_details = $this->orderService->confirmOrder($request->validated(), $order);

            if (!$order_details) {
                return ResponseHelper::error(
                    $order_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new OrderResource($order_details),
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
