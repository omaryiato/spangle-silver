<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\ProductReview;


class ProductReviewRepository
{

    // getProductReviewsList Funtion To Get Product List
    public function getProductReviewsList()
    {
        return ProductReview::with(['user', 'product'])->get();
    }

    // getProductReviewDetails Funtion To Get Product Details
    public function getProductReviewDetails(int $id)
    {
        return ProductReview::with(['user', 'product'])->findorfail($id);
    }

    public function addNewProductReview(array $product_review_request)
    {
        return ProductReview::create($product_review_request);
    }

    public function updateProductReview(ProductReview $productReview, array $product_review_request)
    {
        return $productReview->update($product_review_request);
    }

    // deleteProductReview Funtion To Delete Category
    public function deleteProductReview(ProductReview $productReview)
    {
        $productReview->delete();
        return $productReview;
    }
}

