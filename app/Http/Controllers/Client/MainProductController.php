<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\ShippingMethodResource;
use App\Services\Client\MainProductService;
use Exception;
use Illuminate\Http\Request;

class MainProductController extends Controller
{
    public function __construct(
        protected MainProductService $mainProductService
    ) {}


    public function getProductsList(int $category_id)
    {
        $products_list = $this->mainProductService->getProductsList($category_id);

        return ResponseHelper::success(
            ProductResource::collection($products_list),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function getProductDetails(int $product_id)
    {
        $product_details = $this->mainProductService->getProductDetails($product_id);

        return ResponseHelper::success(
            new ProductResource($product_details),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function getShippingMethodsList()
    {
        $shipping_method_list = $this->mainProductService->getShippingMethodsList();

        return ResponseHelper::success(
            ShippingMethodResource::collection($shipping_method_list),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function getCouponsList()
    {
        $coupons_list = $this->mainProductService->getCouponsList();

        return ResponseHelper::success(
            CouponResource::collection($coupons_list),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function reviewProduct(Request $request)
    {
        try{

            $review_details = $this->mainProductService->reviewProduct($request->all());

            return ResponseHelper::success(
                new ProductReviewResource($review_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => __('validation.exception_error'),
                    'ar' => __('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
