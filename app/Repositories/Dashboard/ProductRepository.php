<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Product;


class ProductRepository
{

    // getProductsList Funtion To Get Product List
    public function getProductsList()
    {
        return Product::with('variants.color', 'variants.size', 'images', 'reviews', 'material', 'stone', 'category')->get();
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails(int $id)
    {
        return Product::with('variants.color', 'variants.size', 'images', 'reviews', 'material', 'stone', 'category')->findorfail($id);
    }

    // addNewProduct Funtion To Add new Product
    // public function addNewProduct($product_request)
    // {

    //     DB::beginTransaction();

    //     try {

    //         $product_id = DB::table('products')
    //                         ->insertGetId([
    //                             'PRODUCT_EN_NAME' => $product_details['product_en_name'],
    //                             'PRODUCT_AR_NAME' => $product_details['product_ar_name'],
    //                             'PRODUCT_EN_description' => $product_details['product_en_description'],
    //                             'PRODUCT_AR_description' => $product_details['product_ar_description'],
    //                             'PRODUCT_IMAGE' => isset($product_details['product_image']) ?
    //                                 str_replace(' ', '_', $product_details['product_en_name']) : null,
    //                             'status' => $product_details['product_status'],
    //                             'product_price' => $product_details['product_price'],
    //                             'product_material' => $product_details['product_material'],
    //                             'product_stone' => $product_details['product_stone'],
    //                             'product_reels' => $product_details['product_reels'],
    //                             'category_id' => $product_details['category_id'],
    //                             'created_by' => $product_details['login_user'],
    //                             'created_at' => now(),
    //                             'updated_by' => $product_details['login_user'],
    //                             'updated_at' => now(),
    //                             'category_id' => $product_details['category_id'],
    //                         ], 'ID');


    //         foreach($product_details['product_images'] as $product_images){
    //             $product_id = DB::table('product_images')
    //                             ->insert([
    //                                 'product_id' => $product_id,
    //                                 'image' => $product_details['image'],
    //                                 'is_primary' => $product_details['is_primary'],
    //                                 'sort_order' => $product_details['sort_order'],
    //                                 'created_by' => $product_details['login_user'],
    //                                 'created_at' => now(),
    //                                 'updated_by' => $product_details['login_user'],
    //                                 'updated_at' => now(),
    //                             ]);

    //             $product_en_name = str_replace(' ', '_', $product_details['product_en_name']);
    //             $product_image_file_extension = $product_details['product_image']?->getClientOriginalExtension();
    //             $product_image_file_name = "{$product_en_name}.{$product_image_file_extension}";

    //             $product_image_folder_path = public_path("documents/category_".$product_details['category_id']."products_images");

    //             if (!File::exists($product_image_folder_path)) {
    //                 File::makeDirectory($product_image_folder_path, 0755, true);
    //             }

    //             $product_details['product_image']->move($product_image_folder_path, $product_image_file_name);

    //         }

    //         foreach($product_details['product_variants'] as $product_variant){
    //             $product_id = DB::table('product_variants')
    //                             ->insert([
    //                                 'product_id' => $product_id,
    //                                 'color_id' => $product_variant['color_id'],
    //                                 'size_id' => $product_variant['size_id'],
    //                                 'sku' => $product_variant['sku'],
    //                                 'stock' => $product_variant['stock'],
    //                                 'price' => $product_variant['price'],
    //                                 'status' => $product_variant['status'],
    //                                 'created_by' => $product_variant['login_user'],
    //                                 'created_at' => now(),
    //                                 'updated_by' => $product_variant['login_user'],
    //                                 'updated_at' => now(),
    //                             ]);
    //         }

    //         return $product_id;

    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         throw $exception;
    //     }

    // }

    public function addNewProduct(array $product_request)
    {
        return Product::create($product_request);
    }

    public function addProductVariants(object $product, array $variants)
    {
        $product->variants()->createMany($variants);
    }

    public function addProductImages(object $product, array $images)
    {
        $product->images()->createMany($images);
    }

    // updateProduct Funtion To Add new Product
    // public function updateProduct($product_details)
    // {
    //     DB::beginTransaction();

    //     try {

    //         //  Update Product
    //         DB::table('products')
    //             ->where('ID', $product_details['product_id'])
    //             ->update([
    //                 'PRODUCT_EN_NAME' => $product_details['product_en_name'],
    //                 'PRODUCT_AR_NAME' => $product_details['product_ar_name'],
    //                 'PRODUCT_EN_DESCRIPTION' => $product_details['product_en_description'],
    //                 'PRODUCT_AR_DESCRIPTION' => $product_details['product_ar_description'],
    //                 'status' => $product_details['product_status'],
    //                 'product_price' => $product_details['product_price'],
    //                 'product_material' => $product_details['product_material'],
    //                 'product_stone' => $product_details['product_stone'],
    //                 'product_reels' => $product_details['product_reels'],
    //                 'category_id' => $product_details['category_id'],
    //                 'updated_by' => $product_details['login_user'],
    //                 'updated_at' => now(),
    //             ]);

    //         // Update Images (Simple Strategy: Delete + Reinsert)

    //         if (isset($product_details['product_images'])) {

    //             DB::table('product_images')
    //                 ->where('product_id', $product_details['product_id'])
    //                 ->delete();

    //             foreach ($product_details['product_images'] as $product_image) {

    //                 DB::table('product_images')->insert([
    //                     'product_id' => $product_details['product_id'],
    //                     'image' => $product_image['image'],
    //                     'is_primary' => $product_image['is_primary'] ?? 0,
    //                     'sort_order' => $product_image['sort_order'] ?? 0,
    //                     'created_by' => $product_details['login_user'],
    //                     'created_at' => now(),
    //                     'updated_by' => $product_details['login_user'],
    //                     'updated_at' => now(),
    //                 ]);

    //                 // رفع الصورة إذا موجودة
    //                 if (isset($product_image['file'])) {

    //                     $product_en_name = str_replace(' ', '_', $product_details['product_en_name']);
    //                     $ext = $product_image['file']->getClientOriginalExtension();

    //                     $file_name = uniqid() . "_{$product_en_name}.{$ext}";

    //                     $path = public_path("documents/category_" . $product_details['category_id'] . "/products_images");

    //                     if (!File::exists($path)) {
    //                         File::makeDirectory($path, 0755, true);
    //                     }

    //                     $product_image['file']->move($path, $file_name);
    //                 }
    //             }
    //         }

    //         // Update Variants (Delete + Reinsert)

    //         if (isset($product_details['product_variants'])) {

    //             DB::table('product_variants')
    //                 ->where('product_id', $product_details['product_id'])
    //                 ->delete();

    //             foreach ($product_details['product_variants'] as $variant) {

    //                 DB::table('product_variants')->insert([
    //                     'product_id' => $product_details['product_id'],
    //                     'color_id' => $variant['color_id'],
    //                     'size_id' => $variant['size_id'],
    //                     'sku' => $variant['sku'],
    //                     'stock' => $variant['stock'],
    //                     'price' => $variant['price'],
    //                     'status' => $variant['status'],
    //                     'created_by' => $product_details['login_user'],
    //                     'created_at' => now(),
    //                     'updated_by' => $product_details['login_user'],
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         }

    //         return true;

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function updateProduct(object $product, array $product_request)
    {
        return $product->update($product_request);
    }

    public function deleteProductImages(object $product)
    {
        $product->images()->delete();
    }

    public function deleteProductVariants(object $product)
    {
        $product->variants()->delete();
    }

    // deleteProduct Funtion To Delete Category
    public function deleteProduct(Product $product)
    {
        $product->delete();
        return $product;
    }
}

