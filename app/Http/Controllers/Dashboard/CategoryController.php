<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\CategoryService;
use App\Http\Resources\Dashboard\CategoryResource;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // getCategorysList Funtion to Get Category List
    public function getCategoryList()
    {
        try {

            $category_list = $this->categoryService->getCategoryList();

            return ResponsHelper::success(CategoryResource::collection($category_list), "Category Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error("Theres somthing wrong",'Error -> ' . $exception->getMessage(),400);
        }
    }

    // getCategoryDetails Funtion to Get Category Details
    public function getCategoryDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'category_id'       => 'bail|required|integer|exists:category,id',
            ],
            [
                'category_id.required'      => trans('ValidationTranslation.category_id_required'),
                'category_id.exists'      =>  trans('ValidationTranslation.category_id_exists'),
                'category_id.integer'      =>  trans('ValidationTranslation.category_id_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $category_id =  $request->category_id;

            $category_details =  $this->categoryService->getCategoryDetails($category_id);

            return ResponsHelper::success(new CategoryResource($category_details), "Category #($category_id) Returned Successfully.", 200);

        } catch (\Exception $exception) {
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // addNewCategory Funtion To Add New Category
    public function addNewCategory(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'category_en_name'       => 'bail|required|unique:category,category_en_name',
                'category_ar_name'       => 'bail|required|unique:category,category_ar_name',
                'category_image'       => 'bail|required|mimes:jpg,png|max:5120',
                'category_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'category_en_name.required'      => trans('ValidationTranslation.category_en_name_required'),
                'category_en_name.unique'      => trans('ValidationTranslation.category_en_name_unique'),
                'category_ar_name.required'      => trans('ValidationTranslation.category_ar_name_required'),
                'category_ar_name.unique'      => trans('ValidationTranslation.category_ar_name_unique'),
                'category_image.required'      => trans('ValidationTranslation.category_image_required'),
                'category_image.mimes'      => trans('ValidationTranslation.category_image_mimes'),
                'category_image.max'      => trans('ValidationTranslation.category_image_max'),
                'category_status.integer'      => trans('ValidationTranslation.category_status_integer'),
                'category_status.in'      => trans('ValidationTranslation.category_status_in'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $category_details=[
                "category_en_name" =>$request->category_en_name,
                "category_ar_name" =>$request->category_ar_name,
                "category_description" =>$request->category_description ?? null,
                "category_image" =>$request->category_image ?? null,
                "category_status" =>$request->category_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_category_details = $this->categoryService->addNewCategory($category_details);

            DB::commit();

            $respons_message = [
                'en' => __('ValidationTranslation.add_new_category', [
                    'category_en_name' => $request->category_en_name
                ], 'en'),
                'ar' => __('ValidationTranslation.add_new_category', [
                    'category_ar_name' => $request->category_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_category_details,$respons_message,201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateCategory Funtion To Update Category
    public function updateCategory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'category_id'       => 'bail|required|integer|exists:category,id',
                'category_en_name'       => 'bail|required|unique:category,category_en_name,' . $request->category_id . ',id',
                'category_ar_name'       => 'bail|required|unique:category,category_ar_name,' . $request->category_id . ',id',
                'category_image'       => 'bail|required|mimes:jpg,png|max:5120',
                'category_status'       => 'bail|integer|in:1,0',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'category_id.required'      => trans('ValidationTranslation.category_id_required'),
                'category_id.exists'      =>  trans('ValidationTranslation.category_id_exists'),
                'category_id.integer'      =>  trans('ValidationTranslation.category_id_integer'),
                'category_en_name.required'      => trans('ValidationTranslation.category_en_name_required'),
                'category_en_name.unique'      => trans('ValidationTranslation.category_en_name_unique'),
                'category_ar_name.required'      => trans('ValidationTranslation.category_ar_name_required'),
                'category_ar_name.unique'      => trans('ValidationTranslation.category_ar_name_unique'),
                'category_image.required'      => trans('ValidationTranslation.category_image_required'),
                'category_image.mimes'      => trans('ValidationTranslation.category_image_mimes'),
                'category_image.max'      => trans('ValidationTranslation.category_image_max'),
                'category_status.integer'      => trans('ValidationTranslation.category_status_integer'),
                'category_status.in'      => trans('ValidationTranslation.category_status_in'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $category_details=[
                "category_id" =>$request->category_id,
                "category_en_name" =>$request->category_en_name,
                "category_ar_name" =>$request->category_ar_name,
                "category_description" =>$request->category_description ?? null,
                "category_image" =>$request->category_image,
                "category_status" =>$request->category_status ?? 1,
                "login_user" =>$request->login_user,
            ];

            $get_category_details =  $this->categoryService->updateCategory($category_details);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.update_category', [
                    'category_en_name' => $request->category_en_name
                ], 'en'),
                'ar' => trans('ValidationTranslation.update_category', [
                    'category_ar_name' => $request->category_ar_name
                ], 'ar'),
            ];

            return ResponsHelper::success($get_category_details,$respons_message,201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteCategory Funtion To Delete Category
    public function deleteCategory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'category_id'       => 'bail|required|integer|exists:category,id',
                'login_user'       => 'bail|required|integer',
            ],
            [
                'category_id.required'      => trans('ValidationTranslation.category_id_required'),
                'category_id.exists'      =>  trans('ValidationTranslation.category_id_exists'),
                'category_id.integer'      =>  trans('ValidationTranslation.category_id_integer'),
                'login_user.required'      => trans('ValidationTranslation.login_user_required'),
                'login_user.integer'      => trans('ValidationTranslation.login_user_integer'),
            ]);

            if ($validator->fails()) {

                $field = $validator->errors()->keys()[0];

                $failedRules = $validator->failed()[$field];
                $rule = strtolower(array_key_first($failedRules));

                $translation_key = 'ValidationTranslation.' . $field . '_' . $rule;

                $response_message = [
                    'en' => trans($translation_key, [], 'en'),
                    'ar' => trans($translation_key, [], 'ar'),
                ];

                return ResponsHelper::error($request->all(),$response_message,400);
            }

            $category_id =  $request->category_id;
            $login_user =  $request->login_user;

            $this->categoryService->deleteCategory($category_id, $login_user);

            DB::commit();

            $respons_message = [
                'en' => trans('ValidationTranslation.delete_category', [
                    'category_id' => $category_id
                ], 'en'),
                'ar' => trans('ValidationTranslation.delete_category', [
                    'category_id' => $category_id
                ], 'ar'),
            ];

            return ResponsHelper::success($category_id, $respons_message, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponsHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
