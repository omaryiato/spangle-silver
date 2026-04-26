<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class OrderRepository
{

    // getOrdersList Funtion To Get Orders List
    public function getOrdersList()
    {
        try {
            return DB::table('orders')->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getOrderDetails Funtion To Get Order Details
    public function getOrderDetails($order_id)
    {

        try {
            return DB::table('orders')
                        ->whereId($order_id)
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // confirmOrder Funtion To Add new Order
    public function confirmOrder($order_id, $order_status, $login_user)
    {

        DB::beginTransaction();

        try {

            $order_id = DB::table('orders')
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

            return $order_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

}

