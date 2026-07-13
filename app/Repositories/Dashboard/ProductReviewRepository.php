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

    // deleteProductReview Funtion To Delete Category
    public function deleteProductReview(ProductReview $productReview)
    {
        $productReview->delete();
        return $productReview;
    }
}

