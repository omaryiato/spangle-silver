<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class CouponRepository
{

    // getCouponsList Funtion To Get Coupons List
    public function getCouponsList()
    {

        try {
            return DB::table('COUPON')->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getCouponDetails Funtion To Get Coupon Details
    public function getCouponDetails($coupon_id)
    {

        try {
            return DB::table('COUPON')
                        ->whereId($coupon_id)
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewCoupon Funtion To Add new Coupon
    public function addNewCoupon($coupon_details)
    {

        DB::beginTransaction();

        try {
            $coupon_id = DB::table('COUPON')
                            ->insertGetId([
                                'code' => $coupon_details['coupon_code'],
                                'discount_amount' => $coupon_details['coupon_discount_amount'],
                                'minimum_order_amount' => $coupon_details['coupon_minimum_order_amount'],
                                'max_usage' => $coupon_details['coupon_max_usage'],
                                'used_count' => $coupon_details['coupon_used_count'],
                                'expires_at' => $coupon_details['coupon_expires_at'],
                                'status' => $coupon_details['coupon_status'],
                                'created_by' => $coupon_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $coupon_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            return $coupon_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateCoupon Funtion To Update Coupon info
    public function updateCoupon($coupon_details)
    {

        DB::beginTransaction();

        try {

            return DB::table('COUPON')
                ->whereId($coupon_details['coupon_id'])
                ->update([
                            'code' => $coupon_details['coupon_code'],
                            'discount_amount' => $coupon_details['coupon_discount_amount'],
                            'minimum_order_amount' => $coupon_details['coupon_minimum_order_amount'],
                            'max_usage' => $coupon_details['coupon_max_usage'],
                            'used_count' => $coupon_details['coupon_used_count'],
                            'expires_at' => $coupon_details['coupon_expires_at'],
                            'status' => $coupon_details['coupon_status'],
                            'updated_by' => $coupon_details['login_user'],
                            'updated_at' => now(),
                ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon($coupon_id, $login_user)
    {

        DB::beginTransaction();

        try {

            return DB::table('COUPON')
                        ->whereId($coupon_id)
                        ->update([
                            'status' => 0,
                            'updated_by' => $login_user,
                            'updated_at' => now(),
                            'deleted_at' => now()
                        ]);
            // return DB::table('COUPON')
            //             ->whereId($coupon_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

