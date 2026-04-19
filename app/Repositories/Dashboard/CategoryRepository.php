<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class CategoryRepository
{

    // getCategoryList Funtion To Get Category List
    public function getCategoryList()
    {

        try {
            return DB::table('category')->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getCategoryDetails Funtion To Get Category Details
    public function getCategoryDetails($category_id)
    {

        try {
            return DB::table('category')
                        ->whereId($category_id)
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewCategory Funtion To Add new Category
    public function addNewCategory($category_details)
    {

        DB::beginTransaction();

        try {

            $category_id = DB::table('category')
                            ->insertGetId([
                                'CATEGORY_EN_NAME' => $category_details['category_en_name'],
                                'CATEGORY_AR_NAME' => $category_details['category_ar_name'],
                                'category_description' => $category_details['category_description'],
                                'CATEGORY_IMAGE' => isset($category_details['category_image']) ?
                                    str_replace(' ', '_', $category_details['category_en_name']) : null,
                                'status' => $category_details['category_status'],
                                'created_by' => $category_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $category_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            if ($category_details['category_image']) {

                $category_en_name = str_replace(' ', '_', $category_details['category_en_name']);
                $category_image_file_extension = $category_details['category_image']?->getClientOriginalExtension();
                $category_image_file_name = "{$category_en_name}.{$category_image_file_extension}";

                $category_image_folder_path = public_path("documents/category_image");

                if (!File::exists($category_image_folder_path)) {
                    File::makeDirectory($category_image_folder_path, 0755, true);
                }

                $category_details['category_image']->move($category_image_folder_path, $category_image_file_name);

                // $output_attachment_path = $category_logo_folder_path . DIRECTORY_SEPARATOR . $category_logo_file_name;
                // $this->uploadDocumnetAcrchive->upload($qiwa_eos_request_info['employee_number'], $output_attachment_path, $attachment_file_name);
            }

            return $category_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateCategory Funtion To Update Category info
    public function updateCategory($category_details)
    {

        DB::beginTransaction();

        try {
            $category_image = null;
            if ($category_details['category_image']) {
                $category_image = str_replace(' ', '_', $category_details['category_en_name']);
            } else {
                $category_image = collect(DB::select("SELECT CATEGORY_IMAGE AS category_image
                                                    FROM CATEGORY WHERE id = :category_id ",
                                            ["category_id" => $category_details['category_id']]))->first()->category_logo ?? null;
            }

            DB::table('category')
                ->whereId($category_details['category_id'])
                ->update([
                            "CATEGORY_EN_NAME" => $category_details['category_en_name'],
                            "CATEGORY_AR_NAME" => $category_details['category_ar_name'],
                            "category_description" => $category_details['category_description'],
                            "CATEGORY_IMAGE" => $category_image,
                            "status" => $category_details['category_status'],
                            "updated_by" => $category_details['login_user'],
                            "updated_at" => now()
                ]);

            if ($category_details['category_image']) {

                $category_en_name = str_replace(' ', '_', $category_details['category_en_name']);
                $category_image_file_extension = $category_details['category_image']?->getClientOriginalExtension();
                $category_image_file_name = "{$category_en_name}.{$category_image_file_extension}";

                $category_image_folder_path = public_path("documents/category_image");

                if (!File::exists($category_image_folder_path)) {
                    File::makeDirectory($category_image_folder_path, 0755, true);
                }

                $category_details['category_image']->move($category_image_folder_path, $category_image_file_name);
            }

            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteCategory Funtion To Delete Category
    public function deleteCategory($category_id, $login_user)
    {

        DB::beginTransaction();

        try {

            DB::table('products')
                ->where('category_id',$category_id)
                ->update([
                    'status' => 0,
                    'updated_by' => $login_user,
                    'updated_at' => now(),
                    'deleted_at' => now()
                ]);
            return DB::table('category')
                        ->whereId($category_id)
                        ->update([
                            'status' => 0,
                            'updated_by' => $login_user,
                            'updated_at' => now(),
                            'deleted_at' => now()
                        ]);
            // return DB::table('category')
            //             ->whereId($category_id)
            //             ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

