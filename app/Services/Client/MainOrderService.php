<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Repositories\Client\MainOrderRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\Dashboard\UserRepository;
use Exception;

class MainOrderService
{
    public function __construct(
        protected MainOrderRepository $mainOrderRepository,
        protected ContactMessageHelper $contactMessageHelper,
        protected UserRepository $userRepository,
    ) {}

    public function getUserOrders(int $user_id)
    {
        return $this->mainOrderRepository->getUserOrders($user_id);
    }

    public function addNewOrder($order_request)
    {

        return DB::transaction(function () use ($order_request) {

            if($order_request['coupon_id'] || $order_request['user_id']){

                $usage_validity = $this->mainOrderRepository->checkCouponUsageValidity($order_request['coupon_id'], $order_request['user_id']);
                if($usage_validity){
                    throw new Exception('Coupon Usage Limit Reached');
                }
            }

            if(!isset($order_request['user_id'])){
                $create_guest_user = [

                    'full_name' => $order_request['snap_user_name'] ?? null,
                    'user_name' => $order_request['snap_user_name'] ?? null,
                    'phone_number' => $order_request['snap_phone'] ?? null,
                    'email_address' => $order_request['snap_email'] ?? null,
                    'password' =>  null,
                    'status' => 1,
                    'user_type' => 3,

                ];

                $user_info = $this->userRepository->addNewUser($create_guest_user);
                $order_request['user_id'] = $user_info->id;

            }

            // 1. Create Order
            $add_new_order = $this->mainOrderRepository->addNewOrder($this->prepareOrderInfo($order_request));

            // 2. Insert Details
            if (!empty($order_request['order_details'])) {

                $details = $this->prepareOrderDetail($order_request);

                $this->mainOrderRepository->addOrderDetails($add_new_order, $details);
            }


            if(!isset($order_request['coupon_id'])){

                $use_coupon = [
                    'coupon_id' => $order_request['coupon_id'] ?? null,
                    'user_id' => $order_request['user_id'] ?? null,
                    'order_id' => $add_new_order->id ?? null,
                ];

                $user_info = $this->mainOrderRepository->addNewCouponUsage($use_coupon);

            }

            // $this->mainOrderRepository->prepareNotificationDetails($add_new_order->id, 'technical_office', $add_new_request->updated_by, 'Approver', $add_new_request->notes);

            return $add_new_order;
        });

    }

    public function prepareOrderInfo(array $order_info)
    {
        $order_data = [
            'user_id' => $order_info['user_id'] ?? null,
            'address_id' => $order_info['address_id'] ?? null,
            'shipping_id' => $order_info['shipping_id'] ?? null,
            'subtotal' => $order_info['subtotal'] ?? null,
            'shipping_cost' => $order_info['shipping_cost'] ?? null,
            'discount' => $order_info['discount'] ?? null,
            'total_price' => $order_info['total_price'] ?? null,
            'status' => $order_info['status'] ?? 0,
            'notes' => $order_info['notes'] ?? null,
            'snap_user_name' => $order_info['snap_user_name'] ?? null,
            'snap_address' => $order_info['snap_address'] ?? null,
            'snap_city' => $order_info['snap_city'] ?? null,
            'snap_country' => $order_info['snap_country'] ?? null,
            'snap_phone' => $order_info['snap_phone'] ?? null,
            'snap_email' => $order_info['snap_email'] ?? null,
            'snap_postal_code' => $order_info['snap_postal_code'] ?? null,
        ];

        if (isset($order_info['created_by'])) {
            $order_data['created_by'] = $order_info['user_id'];
        }

        if (isset($order_info['updated_by'])) {
            $order_data['updated_by'] = $order_info['user_id'];
        }

        return $order_data;
    }

    public function prepareOrderDetail($order_info)
    {
        $details = $order_info['order_details'];

        // if (is_array($details) && isset($details[0]) && is_string($details[0])) {
        //     $details = json_decode($details[0], true);
        // }

        if (is_string($details)) {
            $details = json_decode($details, true);
        }

        return array_map(function ($detail) use($order_info) {
            $order_data = [
                'variant_id' => $detail['variant_id'] ?? null,
                'quantity' => $detail['quantity'] ?? null,
                'unit_price' => $detail['unit_price'] ?? null,
                'total_price' => $detail['unit_price'] ?? null,
            ];

            if (isset($detail['created_by'])) {
                $order_data['created_by'] = $order_info['user_id'];
            }

            if (isset($detail['updated_by'])) {
                $order_data['updated_by'] = $order_info['user_id'];
            }

            return $order_data;
        }, $details);
    }

}
