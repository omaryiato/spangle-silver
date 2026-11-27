<?php

namespace App\Repositories\Client;

use App\Models\Order;

class MainOrderRepository
{
    public function getUserOrders($user_id)
    {
        return Order::where('user_id', $user_id)->with([
            'user',
            'address',
            'shipping',
            'details',
            'details.variant',
            'details.variant.color',
            'details.variant.size',
        ])->get();
    }

    public function addNewOrder($order_request)
    {
        return Order::create($order_request);
    }

    // addRequestDetail Funtion To Add new Request
    public function addOrderDetails(Order $order_request, array $details)
    {
        return $order_request->details()->createMany($details);
    }

    public function lockOwned(Order $order, int $userId): Order
    {
        return Order::whereKey($order->id)->where('user_id', $userId)->lockForUpdate()->firstOrFail();
    }

    public function updateStatus(Order $order, array $data): Order
    {
        $order->update($data);

        return $order;
    }

    public function getOrderDetails(Order $order): Order
    {
        return $order->load(['user', 'address', 'shipping', 'details.variant.color', 'details.variant.size']);
    }
}
