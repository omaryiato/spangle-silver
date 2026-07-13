<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ProductReviewRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class ProductReviewService
{

    protected $productReviewRepository;
    public function __construct(ProductReviewRepository $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    // getProductReviewsList Funtion To Get ProductReview List
    public function getProductReviewsList()
    {
        return  $this->productReviewRepository->getProductReviewsList();
    }

    // getProductReviewDetails Funtion To Get ProductReview Details
    public function getProductReviewDetails(int $product_review_id)
    {
        return $this->productReviewRepository->getProductReviewDetails($product_review_id);
    }

    // deleteProductReview Funtion To Delete ProductReview
    public function deleteProductReview(int $id)
    {
        $product_review_details =  $this->productReviewRepository->getProductReviewDetails($id);
        if(!$product_review_details){
            return null;
        }
        return $this->productReviewRepository->deleteProductReview($product_review_details);
    }

}

