<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\CouponService;
use App\Http\Resources\CouponResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\Coupon\AddCoupon;
use App\Http\Requests\Dashboard\Coupon\UpdateCoupon;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{

    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    // index Funtion to Get Coupons List
    public function index()
    {

        $coupon_list = $this->couponService->getCouponsList();

        return ResponseHelper::success(
            CouponResource::collection($coupon_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    // show Funtion to Get Coupon Details
    public function show(Coupon $coupon)
    {
        $coupon_details =  $this->couponService->getCouponDetails($coupon);

        return ResponseHelper::success(
            new CouponResource($coupon_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    // store Funtion To Add New Coupon
    public function store(AddCoupon $request)
    {

        try {
            $coupon_details = $this->couponService->addNewCoupon($request->validated());

            return ResponseHelper::success(
                new CouponResource($coupon_details),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // update Funtion To Update Coupon
    public function update(UpdateCoupon $request, Coupon $coupon)
    {
        try {

            $coupon_details =  $this->couponService->updateCoupon($request->validated(), $coupon);

            if (!$coupon_details) {
                return ResponseHelper::error(
                    new CouponResource($coupon_details),
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new CouponResource($coupon_details),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
                ],
                201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // deleteCoupon Funtion To Delete Coupon
    public function destroy(Request $request, Coupon $coupon)
    {
        try {

            $coupon_details = $this->couponService->deleteCoupon($request->all(), $coupon);

            if (!$coupon_details) {
                return ResponseHelper::error(
                    $coupon_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted', [], 'en'),
                    'ar' => trans('validation.data_deleted', [], 'ar'),
                ],
                200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
