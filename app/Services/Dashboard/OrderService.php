<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\OrderRepository;
use App\Repositories\MainControlPanelRepository;


class OrderService
{

    protected $orderRepository;
    protected $mainControlPanelRepository;

    public function __construct(
                    OrderRepository $orderRepository,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getOrdersList Funtion To Get Orders List
    public function getOrdersList()
    {
        try {
            return  $this->orderRepository->getOrdersList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getOrderDetails Funtion To Get Order Details
    public function getOrderDetails($order_id)
    {

        try {

            $order_details =  $this->orderRepository->getOrderDetails($order_id);

            return $order_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder($order_id, $order_status, $login_user)
    {

        try {

            $order_id = $this->orderRepository->confirmOrder($order_id, $order_status, $login_user);
            $get_order_details = $this->orderRepository->getOrderDetails($order_id);

            return $get_order_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

