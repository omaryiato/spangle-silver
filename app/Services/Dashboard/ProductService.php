<?php

namespace App\Services\Dashboard;

use App\Helpers\ResponseHelper;
use App\Models\Product;
use App\Repositories\Dashboard\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;


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
    public function getProductDetails(Product $product)
    {
        return $this->productRepository->getProductDetails($product);
    }

    // addNewProduct Funtion To Add new Product
    public function addNewProduct(array $product_request)
    {
        return DB::transaction(function () use ($product_request) {

            if (!empty($product_request['product_reels'])) {
                $reels = $this->uploadProductReels($product_request['product_reels'], $product_request['product_en_name']);
                $product_request['product_reels'] = $reels;
            }

            $product = $this->productRepository->addNewProduct($this->prepareProductRequest($product_request));

            if (!empty($product_request['product_images'])) {
                $images = $this->uploadProductImages($product_request, $product['product_en_name']);
                $this->productRepository->addProductImages($product, $images);
            }

            if (!empty($product_request['product_variants'])) {
                $variants = $this->prepareProductVariants($product_request);
                $this->productRepository->addProductVariants($product, $variants);
            }

            return $product;
        });
    }

    public function updateProduct(array $product_request, Product $product)
    {
        return DB::transaction(function () use ($product_request, $product) {

            // $product = $this->productRepository->getProductDetails($id);

            if (!empty($product_request['product_reels'])) {
                $reels = $this->uploadProductReels($product_request['product_reels'], $product_request['product_en_name']);
                $product_request['product_reels'] = $reels;
            }

            $this->productRepository->updateProduct(
                $product,
                $this->prepareProductRequest($product_request)
            );

            if (isset($product_request['product_images'])) {

                $this->productRepository->deleteProductImages($product);

                $images = $this->uploadProductImages($product_request, $product->product_en_name);

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


    public function prepareProductRequest(array $product_request)
    {
        $request_data = [
            'product_en_name' => $product_request['product_en_name'] ?? null,
            'product_ar_name' => $product_request['product_ar_name'] ?? null,
            'product_en_description' => $product_request['product_en_description'] ?? null,
            'product_ar_description' => $product_request['product_ar_description'] ?? null,
            'product_status' => $product_request['product_status'] ?? null,
            'product_price' => $product_request['product_price'] ?? null,
            'product_material' => $product_request['product_material'] ?? null,
            'product_stone' => $product_request['product_stone'] ?? null,
            'product_reels' => $product_request['product_reels'] ?? null,
            'category_id' => $product_request['category_id'] ?? null,
            'created_by' => $product_request['created_by'] ?? null,
            'updated_by' => $product_request['created_by'] ?? null,
        ];

        if (isset($product_request['created_by'])) {
            $request_data['created_by'] = $product_request['created_by'];
        }


        if (isset($product_request['updated_by'])) {
            $request_data['updated_by'] = $product_request['updated_by'];
        }


        return $request_data;
    }

    public function uploadProductImages(array $product_request, string $product_en_name)
    {
        $product_images = [];

        foreach ($product_request['product_images'] as $product_image) {

            $product_name = str_replace(' ', '_', $product_request['product_en_name']);
            $extension = $product_image['image']->getClientOriginalExtension();
            $file_name = "{$product_name}_" . uniqid() . ".{$extension}";

            $path = public_path("documents/{$product_en_name}_product");

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $webp_name = pathinfo($file_name, PATHINFO_FILENAME) . '.webp';

            $image = Image::decode($product_image['image']);

            // encode to webp
            $encoded = $image->encodeUsingFormat(
                Format::WEBP,
                quality: 85
            );

            // save encoded image
            $encoded->save("{$path}/{$webp_name}");

            // $image['file']->move($path, $file_name);

            $product_images[] = [
                'image' => "{$path}/{$webp_name}",
                'is_primary' => $product_image['is_primary'] ?? 0,
                'sort_order' => $product_image['sort_order'] ?? 0,
                'created_by' => $product_request['updated_by'],
                'updated_by' => $product_request['updated_by'],
            ];
        }

        return $product_images;
    }

    public function prepareProductVariants(array $product_request)
    {

        return array_map(function ($variant) use ($product_request) {
            $request_data = [
                'color_id' => $variant['color_id'] ?? null,
                'size_id' => $variant['size_id'] ?? null,
                'sku' => $variant['sku'] ?? null,
                'stock' => $variant['stock'] ?? null,
                'price' => $variant['price'] ?? null,
                'status' => $variant['status'] ?? null,
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

    // deleteProduct Funtion To Delete Product
    public function deleteProduct(Product $product)
    {
        // $product_details =  $this->productRepository->getProductDetails($id);
        return $this->productRepository->deleteProduct($product);
    }

    protected function uploadProductReels(UploadedFile $file, string $product_en_name): string
    {

        $destination_path = public_path("documents/{$product_en_name}_product/");

        if (!File::exists($destination_path)) {
            File::makeDirectory(
                $destination_path,
                0755,
                true
            );
        }

        // $video_name = pathinfo(
        //     $media_name,
        //     PATHINFO_FILENAME
        // ) . '.mp4';

        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ','_',$product_en_name)
            .'_'.uniqid()
            .'.'.$extension;

        $file->move(
            $destination_path,
            $file_name
        );


        return "documents/{$product_en_name}_product/{$file_name}";
    }

}

