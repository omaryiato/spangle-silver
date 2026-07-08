<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\OrderService;
use App\Http\Resources\OrderResource;
use App\Helpers\ResponseHelper;
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

        return ResponseHelper::success(OrderResource::collection($order_list), "Orders Returned Successfully.", 200);
    }

    // getOrderDetails Funtion to Get Order Details
    public function getOrderDetails($id)
    {
        $order_details =  $this->orderService->getOrderDetails($id);

        return ResponseHelper::success(new OrderResource($order_details), "Order #($id) Returned Successfully.", 200);
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(Request $request, int $id)
    {

        try {

            $get_order_details = $this->orderService->confirmOrder($request->all(), $id);

            return ResponseHelper::success($get_order_details,"respons_message",201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
