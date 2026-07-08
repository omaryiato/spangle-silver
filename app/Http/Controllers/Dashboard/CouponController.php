<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\CouponService;
use App\Http\Resources\CouponResource;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Validator;
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
            "Coupon Returned Successfully.",
            200);
    }

    // show Funtion to Get Coupon Details
    public function show(int $id)
    {
        $coupon_details =  $this->couponService->getCouponDetails($id);

        return ResponseHelper::success(
            new CouponResource($coupon_details),
            "Coupon #($id) Returned Successfully.",
            200);
    }

    // store Funtion To Add New Coupon
    public function store(Request $request)
    {

        try {
            $coupon_details = $this->couponService->addNewCoupon($request->all());

            return ResponseHelper::success($coupon_details,"add new coupon",201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // update Funtion To Update Coupon
    public function update(Request $request, int $id)
    {
        try {

            $coupon_details =  $this->couponService->updateCoupon($request->all(), $id);

            return ResponseHelper::success($coupon_details, "update coupon ",201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteCoupon Funtion To Delete Coupon
    public function deleteCoupon(Request $request, int $id)
    {
        try {

            $this->couponService->deleteCoupon($request->all(), $id);

            return ResponseHelper::success(null, "delete coupon ", 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
