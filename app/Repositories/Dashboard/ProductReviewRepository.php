<?php

namespace App\Repositories\Dashboard;


use App\Models\ProductReview;


class ProductReviewRepository
{

    // getProductReviewsList Funtion To Get Product List
    public function getProductReviewsList()
    {
        return ProductReview::with(['user', 'product'])->get();
    }

    // getProductReviewDetails Funtion To Get Product Details
    public function getProductReviewDetails(object $productReview)
    {
        return $productReview->load(['user', 'product']);
    }

    // deleteProductReview Funtion To Delete Category
    public function deleteProductReview(object $productReview)
    {
        $productReview->delete();
        return $productReview;
    }
}

