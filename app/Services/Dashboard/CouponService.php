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
        return $this->couponRepository->addNewCoupon($coupon_request);
    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon(array $coupon_request, int $id)
    {
        $coupon_details = $this->couponRepository->getCouponDetails($id);
        return $this->couponRepository->updateCoupon($coupon_details, $coupon_request);
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(array $coupon_request, int $id)
    {
        $coupon_details = $this->couponRepository->getCouponDetails($id);
        return $this->couponRepository->deleteCoupon($coupon_details);
    }

}

