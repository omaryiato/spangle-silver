<?php

namespace App\Services\Dashboard;

use App\Models\Coupon;
use App\Repositories\Dashboard\CouponRepository;
use App\Services\ActingUserService;

class CouponService
{
    protected $couponRepository;

    public function __construct(CouponRepository $couponRepository, protected ActingUserService $actingUserService)
    {
        $this->couponRepository = $couponRepository;
    }

    // getCouponsList Funtion To Get Coupons List
    public function getCouponsList()
    {
        return $this->couponRepository->getCouponsList();
    }

    // getCouponDetails Funtion To Get Coupon Details
    public function getCouponDetails(Coupon $coupon)
    {
        return $this->couponRepository->getCouponDetails($coupon);
    }

    // addNewCoupon Funtion To Add new Coupon
    public function addNewCoupon(array $coupon_request)
    {
        return $this->couponRepository->addNewCoupon($this->prepareRequestInfo($coupon_request));
    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon(array $coupon_request, Coupon $coupon)
    {
        // $coupon_details = $this->couponRepository->getCouponDetails($coupon);
        // if(!$coupon_details){
        //     return null;
        // }
        return $this->couponRepository->updateCoupon($coupon,
            $this->prepareRequestInfo($coupon_request, true));
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(Coupon $coupon)
    {
        // $coupon_details = $this->couponRepository->getCouponDetails($coupon);
        // if(!$coupon_details){
        //     return null;
        // }
        return $this->couponRepository->deleteCoupon($coupon);
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {
        $request_data = [
            'code' => $request_info['code'] ?? null,
            'discount_amount' => $request_info['discount_amount'] ?? null,
            'minimum_order_amount' => $request_info['minimum_order_amount'] ?? 0,
            'max_usage' => $request_info['max_usage'] ?? null,
            'used_count' => $request_info['used_count'] ?? 0,
            'expires_at' => $request_info['expires_at'] ?? null,
            'status' => $request_info['status'] ?? 1,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }

    public function getAvailableCoupon(int $id, int $userId, int $subtotalCents): Coupon
    {
        $coupon = $this->couponRepository->lockCoupon($id);
        $total = $this->couponRepository->usageCount($coupon);
        $userUsed = $this->couponRepository->usageCount($coupon, $userId);
        abort_if((int) $coupon->status !== 1 || ($coupon->expires_at && $coupon->expires_at->isPast())
            || $subtotalCents < (int) round((float) $coupon->minimum_order_amount * 100)
            || ($coupon->max_usage !== null && $total >= (int) $coupon->max_usage) || $userUsed > 0, 409);

        return $coupon;
    }

    public function recordOrderUsage(Coupon $coupon, int $userId, int $orderId): void
    {
        $this->couponRepository->recordUsage($coupon, $userId, $orderId);
    }
}
