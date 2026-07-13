<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class ProductService
{

    protected $productRepository;
    public function __construct(ProductRepository $productRepository,)
    {
        $this->productRepository = $productRepository;
    }

    // getProductsList Funtion To Get Product List
    public function getProductsList()
    {
        return  $this->productRepository->getProductsList();
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails(int $product_id)
    {
        return $this->productRepository->getProductDetails($product_id);
    }

    // addNewProduct Funtion To Add new Product
    public function addNewProduct(array $product_request)
    {
        return DB::transaction(function () use ($product_request) {

            $product = $this->productRepository->addNewProduct($this->prepareProductRequest($product_request));

            if (!empty($data['product_images'])) {
                $images = $this->uploadProductImages($product_request, $product['id']);
                $this->productRepository->addProductImages($product, $images);
            }

            if (!empty($data['product_variants'])) {
                $variants = $this->prepareProductVariants($product_request);
                $this->productRepository->addProductVariants($product, $variants);
            }

            return $product;
        });
    }

    public function prepareProductRequest(array $product_request)
    {
        return [
            'product_en_name' => $product_request['product_en_name'],
            'product_ar_name' => $product_request['product_ar_name'],
            'product_en_description' => $product_request['product_en_description'],
            'product_ar_description' => $product_request['product_ar_description'],
            'status' => $product_request['product_status'],
            'product_price' => $product_request['product_price'],
            'product_material' => $product_request['product_material'],
            'product_stone' => $product_request['product_stone'],
            'product_reels' => $product_request['product_reels'],
            'category_id' => $product_request['category_id'],
            'created_by' => $product_request['created_by'],
            'updated_by' => $product_request['created_by'],
        ];
    }

    public function uploadProductImages(array $product_request, int $product_id)
    {
        $product_images = [];

        foreach ($product_request['product_images'] as $image) {

            $product_name = str_replace(' ', '_', $product_request['product_en_name']);
            $extension = $image['file']->getClientOriginalExtension();
            $file_name = "{$product_name}_" . uniqid() . ".{$extension}";

            $path = public_path("documents/product_{$product_id}");

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $image['file']->move($path, $file_name);

            $product_images[] = [
                'image' => $file_name,
                'is_primary' => $image['is_primary'] ?? 0,
                'sort_order' => $image['sort_order'] ?? 0,
                'created_by' => $product_request['created_by'],
                'updated_by' => $product_request['created_by'],
            ];
        }

        return $product_images;
    }

    public function prepareProductVariants(array $product_request)
    {
        return array_map(function ($variant) use ($product_request) {
            $request_data = [
                'color_id' => $variant['color_id'],
                'size_id' => $variant['size_id'],
                'sku' => $variant['sku'],
                'stock' => $variant['stock'],
                'price' => $variant['price'],
                'status' => $variant['status'],
            ];

            if (isset($product_request['created_by'])) {
                $request_data['created_by'] = $product_request['created_by'];
            }

            if (isset($product_request['updated_by'])) {
                $request_data['updated_by'] = $product_request['updated_by'];
            }

            return $request_data;
        }, $product_request['product_variants']);
    }

    public function updateProduct(array $product_request, int $id)
    {
        return DB::transaction(function () use ($product_request, $id) {

            $product = $this->productRepository->getProductDetails($id);

            $this->productRepository->updateProduct(
                $product,
                $this->prepareProductRequest($product_request)
            );

            if (isset($product_request['product_images'])) {

                $this->productRepository->deleteProductImages($product);

                $images = $this->uploadProductImages($product_request, $id);

                $this->productRepository->addProductImages($product, $images);
            }

            if (isset($product_request['product_variants'])) {

                $this->productRepository->deleteProductVariants($product);

                $variants = $this->prepareProductVariants($product_request);

                $this->productRepository->addProductVariants($product, $variants);
            }

            return $product;
        });
    }

    // deleteProduct Funtion To Delete Product
    public function deleteProduct(int $id)
    {
        $product_details =  $this->productRepository->getProductDetails($id);
        return $this->productRepository->deleteProduct($product_details);
    }

}

