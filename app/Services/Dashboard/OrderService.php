<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Repositories\Dashboard\OrderRepository;
use Illuminate\Support\Facades\DB;

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
    public function getOrderDetails(Order $order)
    {
        return $this->orderRepository->getOrderDetails($order);
    }

    // confirmOrder Funtion To Confirm Order
    public function confirmOrder(array $order_request, Order $order)
    {

        DB::beginTransaction();
        try {

            // $order_details = $this->orderRepository->getOrderDetails($order);
            // if(!$order_details){
            //     return null;
            // }
            $this->orderRepository->confirmOrder($order, $order_request);

            $order_details = $order->load('details');

            if($order_request['status'] == 2){

                foreach ($order_details->details as $detail) {

                    $variant = ProductVariant::where('id', $detail->variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        throw new \Exception("Variant not found");
                    }

                    if ($variant->quantity < $detail->quantity) {
                        throw new \Exception("Insufficient stock for variant: {$variant->id}");
                    }

                    $variant->decrement('quantity', $detail->quantity);
                }

                DB::commit();

            }
            
            return true;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

