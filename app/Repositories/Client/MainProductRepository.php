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
                            'products.images',
                            'products.variants' => fn($q) => $q->where('status', 1),
                            'products.variants.color',
                            'products.variants.size',
                            'products.reviews',
                            'products.reviews.user' => fn($q) => $q->where('status', 1),
                            'products.material',
                            'products.stone',
                        ])
                        ->get();
    }

    public function getProductDetails(int $product_id)
    {
        return Product::where('product_status', 1)
                        ->with([
                            'products.images',
                            'products.variants' => fn($q) => $q->where('status', 1),
                            'products.variants.color',
                            'products.variants.size',
                            'products.reviews',
                            'products.reviews.user' => fn($q) => $q->where('status', 1),
                            'products.material',
                            'products.stone',
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
