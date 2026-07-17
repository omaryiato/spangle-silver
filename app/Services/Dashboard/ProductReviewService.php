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
    public function getProductReviewDetails(object $productReview)
    {
        return $this->productReviewRepository->getProductReviewDetails($productReview);
    }

    // deleteProductReview Funtion To Delete ProductReview
    public function deleteProductReview(object $productReview)
    {
        // $product_review_details =  $this->productReviewRepository->getProductReviewDetails($productReview);
        // if(!$product_review_details){
        //     return null;
        // }
        return $this->productReviewRepository->deleteProductReview($productReview);
    }

}

