<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use App\Services\Dashboard\ProductReviewService;
use Exception;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    protected $productReviewService;
    public function __construct(ProductReviewService $productReviewService) {
        $this->productReviewService = $productReviewService;
    }

    public function index()
    {
        $products_review_list = $this->productReviewService->getProductReviewsList();
        return ResponseHelper::success(
            ProductReviewResource::collection($products_review_list),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function show(ProductReview $productReview)
    {
        $products_review_details = $this->productReviewService->getProductReviewDetails($productReview);
        return ResponseHelper::success(
            new ProductReviewResource($products_review_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function destroy(ProductReview $productReview)
    {
        try{

            $products_review_details = $this->productReviewService->deleteProductReview($productReview);

            if (!$products_review_details) {
                return ResponseHelper::error(
                    $products_review_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted'),
                    'ar' => trans('validation.data_deleted'),
                ],
                200);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }
}
