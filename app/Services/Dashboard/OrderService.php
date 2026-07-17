<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\OrderRepository;

class OrderService
{

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    // getOrdersList Funtion To Get Orders List
    public function getOrdersList()
    {
        return  $this->orderRepository->getOrdersList();
    }

    // getOrderDetails Funtion To Get Order Details
    public function getOrderDetails(object $order)
    {
        return $this->orderRepository->getOrderDetails($order);
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(array $order_request, object $order)
    {

        try {

            // $order_details = $this->orderRepository->getOrderDetails($order);
            // if(!$order_details){
            //     return null;
            // }
            return $this->orderRepository->confirmOrder($order, $order_request);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

