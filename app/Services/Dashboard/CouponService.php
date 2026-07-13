<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\CouponRepository;


class CouponService
{

    protected $couponRepository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    // getCouponsList Funtion To Get Coupons List
    public function getCouponsList()
    {
        return  $this->couponRepository->getCouponsList();
    }

    // getCouponDetails Funtion To Get Coupon Details
    public function getCouponDetails(int $id)
    {
        return $this->couponRepository->getCouponDetails($id);
    }

    // addNewCoupon Funtion To Add new Coupon
    public function addNewCoupon(array $coupon_request)
    {
        return $this->couponRepository->addNewCoupon($this->prepareRequestInfo($coupon_request));
    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon(array $coupon_request, int $id)
    {
        $coupon_details = $this->couponRepository->getCouponDetails($id);
        if(!$coupon_details){
            return null;
        }
        return $this->couponRepository->updateCoupon($coupon_details, $this->prepareRequestInfo($coupon_request));
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(array $coupon_request, int $id)
    {
        $coupon_details = $this->couponRepository->getCouponDetails($id);
        if(!$coupon_details){
            return null;
        }
        return $this->couponRepository->deleteCoupon($coupon_details);
    }

    public function prepareRequestInfo(array $request_info)
    {

        $request_data = [
            'code' => $request_info['code'] ?? null,
            'discount_amount' => $request_info['discount_amount'] ?? null,
            'minimum_order_amount' => $request_info['minimum_order_amount'] ?? null,
            'max_usage' => $request_info['max_usage'] ?? null,
            'used_count' => $request_info['used_count'] ?? null,
            'expires_at' => $request_info['expires_at'] ?? null,
            'status' => $request_info['status'] ?? null,
        ];


        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }


        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }


        return $request_data;
    }

}

