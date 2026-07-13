<?php

namespace App\Repositories\Dashboard;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class CouponRepository
{

    // getCouponsList Funtion To Get Coupons List
    public function getCouponsList()
    {
        return Coupon::with([
            'usages',
            'user',
            'order'
        ])->get();
    }

    // getCouponDetails Funtion To Get Coupon Details
    public function getCouponDetails(int $id)
    {
        return Coupon::with([
            'usages',
            'user',
            'order'
        ])->findOrFail($id);
    }

    // addNewCoupon Funtion To Add new Coupon
    public function addNewCoupon(array $coupon_request)
    {
        return Coupon::create($coupon_request);
    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon(Coupon $coupon, array $coupon_request)
    {
        $coupon->update($coupon_request);
        return $coupon;
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return $coupon;
    }
}

