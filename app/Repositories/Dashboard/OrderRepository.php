<?php

namespace App\Repositories\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class OrderRepository
{

    // getOrdersList Funtion To Get Orders List
    public function getOrdersList()
    {
        return Order::with(['user', 'address', 'shipping', 'orderDetail.variant'])->get();
    }

    // getOrderDetails Funtion To Get Order Details
    public function getOrderDetails(int $id)
    {
        return Order::with(['user', 'address', 'shipping', 'orderDetail.variant'])->findOrFail($id);
    }

    // confirmOrder Funtion To Add new Order
    public function confirmOrder(Order $order_details, array $order_request)
    {
        $order_details->update($order_request);
        return $order_details;
    }

}

