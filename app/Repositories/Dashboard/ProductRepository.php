<?php

namespace App\Repositories\Dashboard;


use App\Models\Product;


class ProductRepository
{

    // getProductsList Funtion To Get Product List
    public function getProductsList()
    {
        return Product::with([
            'variants',
            'variants.color',
            'variants.size',
            'images',
            'reviews',
            'reviews.user',
            'material',
            'stone',
            'category'
            ])->get();
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails(Product $product)
    {
        return $product->load([
            'variants',
            'variants.color',
            'variants.size',
            'images',
            'reviews',
            'reviews.user',
            'material',
            'stone',
            'category'
        ]);
    }

    public function addNewProduct(array $product_request)
    {
        return Product::create($product_request);
    }

    public function addProductVariants(Product $product, array $variants)
    {
        return $product->variants()->createMany($variants);
    }

    public function addProductImages(Product $product, array $images)
    {
        return $product->images()->createMany($images);
    }

    public function updateProduct(Product $product, array $product_request)
    {
        $product->update($product_request);
        return $product;
    }

    public function deleteProductImages(Product $product)
    {
        $product->images()->delete();
        return $product;
    }

    public function deleteProductVariants(Product  $product)
    {
        $product->variants()->delete();
        return $product;
    }

    // deleteProduct Funtion To Delete Category
    public function deleteProduct(Product $product)
    {
        // $product->update(['product_status' => 0]);
        // return $product;
        $product->delete();
        return $product;
    }
}

