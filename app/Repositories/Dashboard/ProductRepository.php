<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ProductRepository
{

    // getProductsList Funtion To Get Product List
    public function getProductsList($category_id)
    {
        try {

            // Get Products
            $products = DB::table('products as p')
                ->leftJoin('lookup_values as material', 'p.product_material', '=', 'material.id')
                ->leftJoin('lookup_values as stone', 'p.product_stone', '=', 'stone.id')
                ->where('p.category_id', $category_id)
                ->select(
                    'p.*',
                    'material.meaning as material_name',
                    'stone.meaning as stone_name'
                )
                ->get();

            if ($products->isEmpty()) {
                return [];
            }

            $product_ids = $products->pluck('id');

            // Images
            $images = DB::table('product_images')
                ->whereIn('product_id', $product_ids)
                ->get()
                ->groupBy('product_id');

            // Variants
            $variants = DB::table('product_variants as pv')
                ->leftJoin('lookup_values as color', function ($join) {
                    $join->on('pv.color_id', '=', 'color.id')
                        ->where('color.type_id', 1);
                })
                ->leftJoin('lookup_values as size', function ($join) {
                    $join->on('pv.size_id', '=', 'size.id')
                        ->where('size.type_id', 2);
                })
                ->whereIn('pv.product_id', $product_ids)
                ->select(
                    'pv.*',
                    'color.meaning as color_name',
                    'size.meaning as size_name'
                )
                ->get()
                ->groupBy('product_id');

            // Merge
            $products_list = $products->map(function ($product) use ($images, $variants) {

                return [
                    'id' => $product->id,

                    'product_en_name' => $product->product_en_name,
                    'product_ar_name' => $product->product_ar_name,
                    'product_en_description' => $product->product_en_description,
                    'product_ar_description' => $product->product_ar_description,

                    'product_price' => $product->product_price,
                    'product_status' => (bool) $product->product_status,

                    'created_at' =>  $product->created_at,
                    'created_by' =>  $product->created_by,
                    'updated_at' =>  $product->updated_at,
                    'updated_by' =>  $product->updated_by,
                    'deleted_at' =>  $product->deleted_at,

                    'category_id' => $product->category_id,

                    // material / stone
                    'product_material' => $product->product_material,
                    'material' => $product->material_name,

                    'product_stone' => $product->product_stone,
                    'stone' => $product->stone_name,

                    // images
                    'product_images' => collect($images[$product->id] ?? [])
                        ->sortByDesc('is_primary')
                        ->sortBy('sort_order')
                        ->values()
                        ->map(function ($img) use ($product) {
                            return [
                                'image' => $img->image
                                    ? asset('documents/category_' . $product->category_id . '/products_images/' . $img->image)
                                    : null,
                                'is_primary' => (bool) $img->is_primary,
                                'sort_order' => $img->sort_order,
                            ];
                        }),

                    // variants
                    'product_variants' => collect($variants[$product->id] ?? [])
                        ->values()
                        ->map(function ($variant) {
                            return [
                                'color' => $variant->color_name,
                                'size' => $variant->size_name,
                                'sku' => $variant->sku,
                                'stock' => (int) $variant->stock,
                                'price' => $variant->price,
                                'status' => (bool) $variant->status,
                            ];
                        }),
                ];
            });

            return $products_list;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails($product_id)
    {

        try {

            // Get Product
            $product = DB::table('products as p')
                ->leftJoin('lookup_values as material', 'p.product_material', '=', 'material.id')
                ->leftJoin('lookup_values as stone', 'p.product_stone', '=', 'stone.id')
                ->where('p.id', $product_id)
                ->select(
                    'p.*',
                    'material.meaning as material_name',
                    'stone.meaning as stone_name'
                )
                ->get();

            $product_id = $product->pluck('id');

            // Images
            $images = DB::table('product_images')
                ->whereIn('product_id', $product_id)
                ->get()
                ->groupBy('product_id');

            // Variants
            $variants = DB::table('product_variants as pv')
                ->leftJoin('lookup_values as color', function ($join) {
                    $join->on('pv.color_id', '=', 'color.id')
                        ->where('color.type_id', 1);
                })
                ->leftJoin('lookup_values as size', function ($join) {
                    $join->on('pv.size_id', '=', 'size.id')
                        ->where('size.type_id', 2);
                })
                ->whereIn('pv.product_id', $product_id)
                ->select(
                    'pv.*',
                    'color.meaning as color_name',
                    'size.meaning as size_name'
                )
                ->get()
                ->groupBy('product_id');

            // Merge
            $product_details = $product->map(function ($product) use ($images, $variants) {

                return [
                    'id' => $product->id,

                    'product_en_name' => $product->product_en_name,
                    'product_ar_name' => $product->product_ar_name,
                    'product_en_description' => $product->product_en_description,
                    'product_ar_description' => $product->product_ar_description,

                    'product_price' => $product->product_price,
                    'product_status' => (bool) $product->product_status,

                    'created_at' =>  $product->created_at,
                    'created_by' =>  $product->created_by,
                    'updated_at' =>  $product->updated_at,
                    'updated_by' =>  $product->updated_by,
                    'deleted_at' =>  $product->deleted_at,

                    'category_id' => $product->category_id,

                    // material / stone
                    'product_material' => $product->product_material,
                    'material' => $product->material_name,

                    'product_stone' => $product->product_stone,
                    'stone' => $product->stone_name,

                    // images
                    'product_images' => collect($images[$product->id] ?? [])
                        ->sortByDesc('is_primary')
                        ->sortBy('sort_order')
                        ->values()
                        ->map(function ($img) use ($product) {
                            return [
                                'image' => $img->image
                                    ? asset('documents/category_' . $product->category_id . '/products_images/' . $img->image)
                                    : null,
                                'is_primary' => (bool) $img->is_primary,
                                'sort_order' => $img->sort_order,
                            ];
                        }),

                    // variants
                    'product_variants' => collect($variants[$product->id] ?? [])
                        ->values()
                        ->map(function ($variant) {
                            return [
                                'color' => $variant->color_name,
                                'size' => $variant->size_name,
                                'sku' => $variant->sku,
                                'stock' => (int) $variant->stock,
                                'price' => $variant->price,
                                'status' => (bool) $variant->status,
                            ];
                        }),
                ];
            });

            return $product_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewProduct Funtion To Add new Product
    public function addNewProduct($product_details)
    {

        DB::beginTransaction();

        try {

            $product_id = DB::table('PRODUCTS')
                            ->insertGetId([
                                'PRODUCT_EN_NAME' => $product_details['product_en_name'],
                                'PRODUCT_AR_NAME' => $product_details['product_ar_name'],
                                'PRODUCT_EN_description' => $product_details['product_en_description'],
                                'PRODUCT_AR_description' => $product_details['product_ar_description'],
                                'PRODUCT_IMAGE' => isset($product_details['product_image']) ?
                                    str_replace(' ', '_', $product_details['product_en_name']) : null,
                                'status' => $product_details['product_status'],
                                'product_price' => $product_details['product_price'],
                                'product_material' => $product_details['product_material'],
                                'product_stone' => $product_details['product_stone'],
                                'product_reels' => $product_details['product_reels'],
                                'category_id' => $product_details['category_id'],
                                'created_by' => $product_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $product_details['login_user'],
                                'updated_at' => now(),
                                'category_id' => $product_details['category_id'],
                            ], 'ID');


            foreach($product_details['product_images'] as $product_images){
                $product_id = DB::table('product_images')
                                ->insert([
                                    'product_id' => $product_id,
                                    'image' => $product_details['image'],
                                    'is_primary' => $product_details['is_primary'],
                                    'sort_order' => $product_details['sort_order'],
                                    'created_by' => $product_details['login_user'],
                                    'created_at' => now(),
                                    'updated_by' => $product_details['login_user'],
                                    'updated_at' => now(),
                                ]);

                $product_en_name = str_replace(' ', '_', $product_details['product_en_name']);
                $product_image_file_extension = $product_details['product_image']?->getClientOriginalExtension();
                $product_image_file_name = "{$product_en_name}.{$product_image_file_extension}";

                $product_image_folder_path = public_path("documents/category_".$product_details['category_id']."products_images");

                if (!File::exists($product_image_folder_path)) {
                    File::makeDirectory($product_image_folder_path, 0755, true);
                }

                $product_details['product_image']->move($product_image_folder_path, $product_image_file_name);

            }

            foreach($product_details['product_variants'] as $product_variant){
                $product_id = DB::table('product_variants')
                                ->insert([
                                    'product_id' => $product_id,
                                    'color_id' => $product_variant['color_id'],
                                    'size_id' => $product_variant['size_id'],
                                    'sku' => $product_variant['sku'],
                                    'stock' => $product_variant['stock'],
                                    'price' => $product_variant['price'],
                                    'status' => $product_variant['status'],
                                    'created_by' => $product_variant['login_user'],
                                    'created_at' => now(),
                                    'updated_by' => $product_variant['login_user'],
                                    'updated_at' => now(),
                                ]);
            }

            return $product_id;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateProduct Funtion To Add new Product
    public function updateProduct($product_details)
    {
        DB::beginTransaction();

        try {

            //  Update Product
            DB::table('PRODUCTS')
                ->where('ID', $product_details['product_id'])
                ->update([
                    'PRODUCT_EN_NAME' => $product_details['product_en_name'],
                    'PRODUCT_AR_NAME' => $product_details['product_ar_name'],
                    'PRODUCT_EN_DESCRIPTION' => $product_details['product_en_description'],
                    'PRODUCT_AR_DESCRIPTION' => $product_details['product_ar_description'],
                    'status' => $product_details['product_status'],
                    'product_price' => $product_details['product_price'],
                    'product_material' => $product_details['product_material'],
                    'product_stone' => $product_details['product_stone'],
                    'product_reels' => $product_details['product_reels'],
                    'category_id' => $product_details['category_id'],
                    'updated_by' => $product_details['login_user'],
                    'updated_at' => now(),
                ]);

            // Update Images (Simple Strategy: Delete + Reinsert)

            if (isset($product_details['product_images'])) {

                DB::table('product_images')
                    ->where('product_id', $product_details['product_id'])
                    ->delete();

                foreach ($product_details['product_images'] as $product_image) {

                    DB::table('product_images')->insert([
                        'product_id' => $product_details['product_id'],
                        'image' => $product_image['image'],
                        'is_primary' => $product_image['is_primary'] ?? 0,
                        'sort_order' => $product_image['sort_order'] ?? 0,
                        'created_by' => $product_details['login_user'],
                        'created_at' => now(),
                        'updated_by' => $product_details['login_user'],
                        'updated_at' => now(),
                    ]);

                    // رفع الصورة إذا موجودة
                    if (isset($product_image['file'])) {

                        $product_en_name = str_replace(' ', '_', $product_details['product_en_name']);
                        $ext = $product_image['file']->getClientOriginalExtension();

                        $file_name = uniqid() . "_{$product_en_name}.{$ext}";

                        $path = public_path("documents/category_" . $product_details['category_id'] . "/products_images");

                        if (!File::exists($path)) {
                            File::makeDirectory($path, 0755, true);
                        }

                        $product_image['file']->move($path, $file_name);
                    }
                }
            }

            // Update Variants (Delete + Reinsert)

            if (isset($product_details['product_variants'])) {

                DB::table('product_variants')
                    ->where('product_id', $product_details['product_id'])
                    ->delete();

                foreach ($product_details['product_variants'] as $variant) {

                    DB::table('product_variants')->insert([
                        'product_id' => $product_details['product_id'],
                        'color_id' => $variant['color_id'],
                        'size_id' => $variant['size_id'],
                        'sku' => $variant['sku'],
                        'stock' => $variant['stock'],
                        'price' => $variant['price'],
                        'status' => $variant['status'],
                        'created_by' => $product_details['login_user'],
                        'created_at' => now(),
                        'updated_by' => $product_details['login_user'],
                        'updated_at' => now(),
                    ]);
                }
            }

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // deleteProduct Funtion To Delete Category
    public function deleteProduct($product_id, $login_user)
    {

        DB::beginTransaction();

        try {

            DB::table('PRODUCTS')
                ->whereId($product_id)
                ->update([
                    'status' => 0,
                    'updated_by' => $login_user,
                    'updated_at' => now(),
                    'deleted_at' => now()
                ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

