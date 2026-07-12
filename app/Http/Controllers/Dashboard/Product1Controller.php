<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\ProductService;
use App\Http\Resources\ProductResource;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // getProductsList Funtion to Get Product List
    public function getProductsList()
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'category_id'       => 'bail|required|integer|exists:category,id',
            // ],
            // [
            //     'category_id.required'      => trans('validation.category_id_required'),
            //     'category_id.exists'      =>  trans('validation.category_id_exists'),
            //     'category_id.integer'      =>  trans('validation.category_id_integer'),
            // ]);

            // if ($validator->fails()) {

            //     $field = $validator->errors()->keys()[0];

            //     $failedRules = $validator->failed()[$field];
            //     $rule = strtolower(array_key_first($failedRules));

            //     $translation_key = 'validation.' . $field . '_' . $rule;

            //     $response_message = [
            //         'en' => trans($translation_key, [], 'en'),
            //         'ar' => trans($translation_key, [], 'ar'),
            //     ];

            //     return ResponseHelper::error($request->all(),$response_message,400);
            // }


            $Product_list = $this->productService->getProductsList();

            return ResponseHelper::success(ProductResource::collection($Product_list),
            "Product Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponseHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getProductDetails Funtion to Get Product Details
    public function getProductDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'product_id'       => 'bail|required|integer|exists:products,id',
            ],
            [
                'product_id.required'      => trans('validation.product_id_required'),
                'product_id.exists'      =>  trans('validation.product_id_exists'),
                'product_id.integer'      =>  trans('validation.product_id_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'validation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponseHelper::error($request->all(),$response_message,400);
            }

            $product_id =  $request->product_id;

            $product_details =  $this->productService->getProductDetails($product_id);

            return ResponseHelper::success(new ProductResource($product_details), "Product #($product_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewProduct Funtion To Add New Product
    public function addNewProduct(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'category_id'       => 'bail|required|integer|exists:category,id',
                'product_en_name'       => 'bail|required|unique:products,product_en_name',
                'product_ar_name'       => 'bail|required|unique:products,product_ar_name',
                'product_en_description'       => 'bail|required',
                'product_ar_description'       => 'bail|required',
                'product_material'       => 'bail|required|integer|exists:lookup_values,code',
                'product_stone'       => 'bail|required|integer|exists:lookup_values,code',
                'product_price'       => 'bail|required|numaric',
                'product_images'       => 'bail|required|array|min:1',
                'product_images.image'       => 'bail|mimes:jpg,png|max:5120',
                'product_images.is_primary'       => 'bail|integer|in:1,0',
                'product_images.sort_order'       => 'bail|integer',
                'product_variants'       => 'bail|required|array|min:1',
                'product_variants.color_id'       => 'bail|required|integer|exists:lookup_values,code',
                'product_variants.size_id'       => 'bail|required|integer|exists:lookup_values,code',
                'product_variants.stock'       => 'bail|required|integer',
                'product_variants.price'       => 'bail|numaric',
                'product_variants.sku'       => 'bail|required|unique:product_variants,' . $request->sku,
                'product_variants.status'       => 'bail|integer|in:1,0',
                'product_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'category_id.required'      => trans('validation.category_id_required'),
                'category_id.exists'      =>  trans('validation.category_id_exists'),
                'category_id.integer'      =>  trans('validation.category_id_integer'),

                'product_en_name.required'      => trans('validation.product_en_name_required'),
                'product_en_name.unique'      => trans('validation.product_en_name_unique'),
                'product_ar_name.required'      => trans('validation.product_ar_name_required'),
                'product_ar_name.unique'      => trans('validation.product_ar_name_unique'),
                'product_image.required'      => trans('validation.product_image_required'),
                'product_image.min'      => trans('validation.product_image_min'),
                'product_image.image.mimes'      => trans('validation.product_image_mimes'),
                'product_image.image.max'      => trans('validation.product_image_max'),
                'product_image.is_primary.integer'      => trans('validation.product_status_integer'),
                'product_image.sort_order.integer'      => trans('validation.product_status_integer'),
                'product_status.integer'      => trans('validation.product_status_integer'),
                'product_status.in'      => trans('validation.product_status_in'),
                'login_user.required'      => trans('validation.login_user_required'),
                'login_user.integer'      => trans('validation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'validation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponseHelper::error($request->all(),$response_message,400);
            }

            $product_details=[
                "category_id" =>$request->category_id,
                "product_en_name" =>$request->product_en_name,
                "product_ar_name" =>$request->product_ar_name,
                "product_en_description" =>$request->product_en_description,
                "product_ar_description" =>$request->product_ar_description,
                "product_images" =>$request->product_images,
                "product_variants" =>$request->product_variants,
                "product_status" =>$request->product_status ?? 1,
                "product_reels" =>$request->product_reels ?? null,
                "product_material" =>$request->product_material,
                "product_stone" =>$request->product_stone,
                "product_price" =>$request->product_price,
                "product_status" =>$request->product_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_product_details = $this->productService->addNewProduct($product_details);

            DB::commit();

            $respons_message = [
                'en' => trans('validation.add_new_product', [
                    'product_en_name' => $request->product_en_name
                ], 'en'),
                'ar' => trans('validation.add_new_product', [
                    'product_ar_name' => $request->product_ar_name
                ], 'ar'),
            ];

            return ResponseHelper::success($get_product_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateProduct Funtion To Update Product
    public function updateProduct(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'category_id'       => 'bail|required|integer|exists:category,id',
                'product_id'       => 'bail|required|integer|exists:products,id',
                'product_en_name'       => 'bail|required|unique:products,product_en_name,' . $request->product_id,
                'product_ar_name'       => 'bail|required|unique:products,product_ar_name,' . $request->product_id,
                'product_en_description'       => 'bail|required',
                'product_ar_description'       => 'bail|required',
                'product_material'       => 'bail|required|integer|exists:lookup_values,code',
                'product_stone'       => 'bail|required|integer|exists:lookup_values,code',
                'product_price'       => 'bail|required|numaric',
                'product_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'category_id.required'      => trans('validation.category_id_required'),
                'category_id.exists'      =>  trans('validation.category_id_exists'),
                'category_id.integer'      =>  trans('validation.category_id_integer'),

                'product_id.required'      => trans('validation.product_id_required'),
                'product_id.exists'      =>  trans('validation.product_id_exists'),
                'product_id.integer'      =>  trans('validation.product_id_integer'),
                'product_en_name.required'      => trans('validation.product_en_name_required'),
                'product_en_name.unique'      => trans('validation.product_en_name_unique'),
                'product_ar_name.required'      => trans('validation.product_ar_name_required'),
                'product_ar_name.unique'      => trans('validation.product_ar_name_unique'),
                'product_status.integer'      => trans('validation.product_status_integer'),
                'product_status.in'      => trans('validation.product_status_in'),
                'login_user.required'      => trans('validation.login_user_required'),
                'login_user.integer'      => trans('validation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'validation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponseHelper::error($request->all(),$response_message,400);
            }

            $product_details=[
                "category_id" =>$request->category_id,
                "product_id" =>$request->product_id,
                "product_en_name" =>$request->product_en_name,
                "product_ar_name" =>$request->product_ar_name,
                "product_en_description" =>$request->product_description,
                "product_ar_description" =>$request->product_description,
                "product_images" =>$request->product_images ?? null,
                "product_status" =>$request->product_status ?? 1,
                "product_reels" =>$request->product_reels ?? null,
                "product_material" =>$request->product_material,
                "product_stone" =>$request->product_stone,
                "product_price" =>$request->product_price,
                "product_status" =>$request->product_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_product_details =  $this->productService->updateProduct($product_details);

            DB::commit();

            $respons_message = [
                'en' => trans('validation.update_Product', [
                    'product_en_name' => $request->product_en_name
                ], 'en'),
                'ar' => trans('validation.update_Product', [
                    'product_ar_name' => $request->product_ar_name
                ], 'ar'),
            ];

            return ResponseHelper::success($get_product_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteProduct Funtion To Delete Product
    public function deleteProduct(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'product_id'       => 'bail|required|integer|exists:products,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'product_id.required'      => trans('validation.product_id_required'),
                'product_id.exists'      =>  trans('validation.product_id_exists'),
                'product_id.integer'      =>  trans('validation.product_id_integer'),
                'login_user.required'      => trans('validation.login_user_required'),
                'login_user.integer'      => trans('validation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'validation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponseHelper::error($request->all(),$response_message,400);
            }

            $product_id =  $request->product_id;
            $login_user =  $request->login_user;

            $this->productService->deleteProduct($product_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('validation.delete_product', [
                    'product_id' => $product_id
                ], 'en'),
                'ar' => trans('validation.delete_product', [
                    'product_id' => $product_id
                ], 'ar'),
            ];

            return ResponseHelper::success($product_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
