<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ReviewProduct;
use App\Http\Resources\CouponResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\ShippingMethodResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\Client\MainProductService;
use Exception;
use Illuminate\Http\Request;

class MainProductController extends Controller
{
    public function __construct(
        protected MainProductService $mainProductService
    ) {}


    public function getProductsList(Category $category)
    {
        $products_list = $this->mainProductService->getProductsList($category->id);

        return ResponseHelper::success(
            ProductResource::collection($products_list),
            [
                'en' => trans('validation.get_products_list', [], 'en'),
                'ar' => trans('validation.get_products_list', [], 'ar'),
            ],
            200
        );
    }


    public function getProductDetails(Product $product)
    {
        $product_details = $this->mainProductService->getProductDetails($product);

        return ResponseHelper::success(
            new ProductResource($product_details),
            [
                'en' => trans('validation.get_product_details', [], 'en'),
                'ar' => trans('validation.get_product_details', [], 'ar'),
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
                'en' => trans('validation.get_shipping_methods_list', [], 'en'),
                'ar' => trans('validation.get_shipping_methods_list', [], 'ar'),
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
                'en' => trans('validation.get_coupons_list', [], 'en'),
                'ar' => trans('validation.get_coupons_list', [], 'ar'),
            ],
            200
        );
    }

    public function reviewProduct(ReviewProduct $request)
    {
        try{

            $review_details = $this->mainProductService->reviewProduct($request->validated());

            return ResponseHelper::success(
                new ProductReviewResource($review_details),
                [
                    'en' => trans('validation.review_product', [], 'en'),
                    'ar' => trans('validation.review_product', [], 'ar'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function stream(Product $product)
    {
        $product_reel = $this->mainProductService->stream($product);

        if(!$product_reel){
            return ResponseHelper::error(
                    $product_reel,
                    [
                        'en' => trans('validation.no_data_found', [], 'en'),
                        'ar' => trans('validation.no_data_found', [], 'ar'),
                    ],
                    404);
        }

        return $product_reel;
    }

}
