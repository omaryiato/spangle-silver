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
        return Order::with([
            'user',
            'address',
            'shipping',
            'details',
            'details.variant',
            'details.variant.product',
            'details.variant.color',
            'details.variant.size',
            'user',
        ])->get();
    }

    // getOrderDetails Funtion To Get Order Details
    public function getOrderDetails(object $order)
    {
        return $order->load([
            'user',
            'address',
            'shipping',
            'details',
            'details.variant',
            'details.variant.product',
            'details.variant.color',
            'details.variant.size',
            'user',
        ]);
    }

    // confirmOrder Funtion To Add new Order
    public function confirmOrder(object $order, array $order_request)
    {
        $order->update($order_request);
        return $order;
    }

}

