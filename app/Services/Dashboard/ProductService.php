<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use App\Repositories\Dashboard\ProductRepository;
use App\Services\ActingUserService;
use App\Services\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository, protected ActingUserService $actingUserService, protected MediaStorageService $mediaStorage)
    {
        $this->productRepository = $productRepository;
    }

    // getProductsList Funtion To Get Product List
    public function getProductsList()
    {
        return $this->productRepository->getProductsList();
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails(Product $product)
    {
        return $this->productRepository->getProductDetails($product);
    }

    // addNewProduct Funtion To Add new Product
    public function addNewProduct(array $product_request)
    {
        $newPaths = [];
        try {
            return DB::transaction(function () use ($product_request, &$newPaths) {
                if (($product_request['product_reels'] ?? null) instanceof UploadedFile) {
                    $product_request['product_reels'] = $this->uploadProductReels($product_request['product_reels'], $product_request['product_en_name'], $product_request);
                    $newPaths[] = $product_request['product_reels'];
                }
                $product = $this->productRepository->addNewProduct($this->prepareProductRequest($product_request));
                if (! empty($product_request['product_images'])) {
                    $images = $this->uploadProductImages($product_request, $product->product_en_name);
                    foreach ($images as $image) {
                        $newPaths[] = $image['image'];
                    }
                    $this->productRepository->addProductImages($product, $images);
                }
                if (! empty($product_request['product_variants'])) {
                    $this->productRepository->addProductVariants($product, $this->prepareProductVariants($product_request));
                }

                return $this->productRepository->getProductDetails($product);
            });
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) {
                $this->mediaStorage->delete($path);
            }
            throw $exception;
        }
    }

    public function updateProduct(array $product_request, Product $product)
    {
        $newPaths = [];
        $oldReels = $product->product_reels;
        $replacedReels = false;
        try {
            $result = DB::transaction(function () use ($product_request, $product, &$newPaths, &$replacedReels) {
                if (($product_request['product_reels'] ?? null) instanceof UploadedFile) {
                    $product_request['product_reels'] = $this->uploadProductReels($product_request['product_reels'], $product->product_en_name, $product_request);
                    $newPaths[] = $product_request['product_reels'];
                    $replacedReels = true;
                } else {
                    unset($product_request['product_reels']);
                }
                $this->productRepository->updateProduct($product, $this->prepareProductRequest($product_request, true));
                // New images are appended. Missing images and variants are never deleted implicitly.
                if (! empty($product_request['product_images'])) {
                    $images = $this->uploadProductImages($product_request, $product->product_en_name);
                    foreach ($images as $image) {
                        $newPaths[] = $image['image'];
                    }
                    $this->productRepository->addProductImages($product, $images);
                }
                if (! empty($product_request['product_variants'])) {
                    $this->productRepository->saveProductVariants($product, $this->prepareProductVariants($product_request));
                }

                return $this->productRepository->getProductDetails($product);
            });
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) {
                $this->mediaStorage->delete($path);
            }
            throw $exception;
        }
        if ($replacedReels) {
            $this->mediaStorage->delete($oldReels);
        }

        return $result;
    }

    public function prepareProductRequest(array $product_request, bool $updating = false)
    {
        $request_data = [
            'product_en_name' => $product_request['product_en_name'] ?? null,
            'product_ar_name' => $product_request['product_ar_name'] ?? null,
            'product_en_description' => $product_request['product_en_description'] ?? null,
            'product_ar_description' => $product_request['product_ar_description'] ?? null,
            'product_status' => $product_request['product_status'] ?? 1,
            'product_price' => $product_request['product_price'] ?? 0,
            'product_reels' => $product_request['product_reels'] ?? null,
            'product_material' => $product_request['product_material'] ?? null,
            'product_stone' => $product_request['product_stone'] ?? null,
            'category_id' => $product_request['category_id'] ?? null,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $product_request);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }

    public function uploadProductImages(array $product_request, string $product_en_name): array
    {
        $images = [];
        try {
            foreach ($product_request['product_images'] as $image) {
                $images[] = $this->actingUserService->attributes([
                    'image' => $this->mediaStorage->store($image['image'], 'products/images', true),
                    'is_primary' => $image['is_primary'] ?? 0, 'sort_order' => $image['sort_order'] ?? 0,
                ]);
            }
        } catch (\Throwable $exception) {
            foreach ($images as $image) {
                $this->mediaStorage->delete($image['image']);
            }
            throw $exception;
        }

        return $images;
    }

    public function prepareProductVariants(array $product_request): array
    {
        $variants = [];
        foreach ($product_request['product_variants'] as $variant) {
            $updating = isset($variant['id']);
            $data = array_intersect_key($variant, array_flip(['id', 'color_id', 'size_id', 'sku', 'stock', 'price', 'status']));
            if (! $updating) {
                $data += ['stock' => 0, 'price' => null, 'status' => 1];
            }
            $variants[] = $this->actingUserService->attributes($data, $updating);
        }

        return $variants;
    }

    // deleteProduct Funtion To Delete Product
    public function deleteProduct(Product $product)
    {
        // $product_details =  $this->productRepository->getProductDetails($id);
        return $this->productRepository->deleteProduct($product);
    }

    protected function uploadProductReels(UploadedFile $file, string $product_en_name, array $product_request): string
    {
        return $this->mediaStorage->store($file, 'products/reels');
    }
}
