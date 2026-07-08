<?php

namespace App\Repositories\Client;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShippingMethod;


class MainProductRepository
{


    public function getProductsList(int $category_id)
    {
        return Product::where('product_status', 1)
                        ->where('category_id', $category_id)
                        ->with([
                            'category',
                            'images',
                            'variants' => fn($q) => $q->where('status', 1),
                            'variants.color',
                            'variants.size',
                            'reviews',
                            'reviews.user' => fn($q) => $q->where('status', 1),
                            'material',
                            'stone',
                        ])
                        ->get();
    }

    public function getProductDetails(int $product_id)
    {
        return Product::where('product_status', 1)
                        ->with([
                            'category',
                            'images',
                            'variants' => fn($q) => $q->where('status', 1),
                            'variants.color',
                            'variants.size',
                            'reviews',
                            'reviews.user' => fn($q) => $q->where('status', 1),
                            'material',
                            'stone',
                        ])
                        ->findOrFail($product_id);
    }

    public function getShippingMethodsList()
    {
        return ShippingMethod::where('status', 1)->get();
    }

    public function getCouponsList()
    {
        return Coupon::where('status', 1)->get();
    }

    public function reviewProduct($review_request)
    {
        return ProductReview::create($review_request);
    }

}
