<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
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
        return ResponseHelper::success(ProductReviewResource::collection($products_review_list), "Product list returned Successfully.", 200);
    }

    public function show(int $id)
    {
        $products_review_details = $this->productReviewService->getProductReviewDetails($id);
        return ResponseHelper::success($products_review_details, "Product Details returned Successfully.", 200);
    }

    public function store(Request $request)
    {
        try{
            $product_details = $this->productReviewService->addNewProductReview($request->all());
            return ResponseHelper::success($product_details,"Product Added Successfully", 201);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }


    public function update(Request $request, int $id)
    {
        try{
            $product_details = $this->productReviewService->updateProductReview($request->all(), $id);
            return ResponseHelper::success($product_details,"Product Updated Successfully.", 201);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->productReviewService->deleteProductReview($id);
            return ResponseHelper::success(null,"Product Deleted Successfully.", 200);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }
}
