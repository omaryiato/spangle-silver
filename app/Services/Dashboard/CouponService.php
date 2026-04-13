<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\CouponRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class CouponService
{

    protected $couponRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    CouponRepository $couponRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getCouponsList Funtion To Get Coupons List
    public function getCouponsList()
    {
        try {
            return  $this->couponRepository->getCouponsList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getCouponDetails Funtion To Get Coupon Details
    public function getCouponDetails($coupon_id)
    {

        try {

            $coupon_details =  $this->couponRepository->getCouponDetails($coupon_id);

            return $coupon_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewCoupon Funtion To Add new Coupon
    public function addNewCoupon($coupon_details)
    {

        try {

            $coupon_id = $this->couponRepository->addNewCoupon($coupon_details);
            $get_coupon_details = $this->couponRepository->getCouponDetails($coupon_id);

            return $get_coupon_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon($coupon_details)
    {

        try {
            $this->couponRepository->updateCoupon($coupon_details);
            $get_coupon_details = $this->couponRepository->getCouponDetails($coupon_details['coupon_id']);

            return $get_coupon_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon($coupon_id, $login_user)
    {
        try {

            return $this->couponRepository->deleteCoupon($coupon_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

