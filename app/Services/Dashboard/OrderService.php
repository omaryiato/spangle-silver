<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\OrderRepository;
use App\Repositories\MainControlPanelRepository;


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
    public function getOrderDetails(int $id)
    {

        return $this->orderRepository->getOrderDetails($id);

    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder($order_request, int $id)
    {

        try {

            $order_details = $this->orderRepository->getOrderDetails($id);
            return $this->orderRepository->confirmOrder($order_details, $order_request);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

