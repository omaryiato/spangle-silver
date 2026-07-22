<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\CategoryService;
use App\Http\Resources\CategoryResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\Category\AddCategory;
use App\Http\Requests\Dashboard\Category\UpdateCategory;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $category_list  = $this->categoryService->getCategoryList();
        return ResponseHelper::success(
            CategoryResource::collection($category_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    public function show(Category $category)
    {
        $category_details = $this->categoryService->getCategoryDetails($category);

        return ResponseHelper::success(
            new CategoryResource($category_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);
    }

    public function store(AddCategory $request)
    {
        try{
            $category_details = $this->categoryService->addNewCategory($request);
            return ResponseHelper::success(
                new CategoryResource($category_details),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                201);

        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }
    public function update(UpdateCategory $request, Category $category)
    {
        try{
            $category_details = $this->categoryService->updateCategoryDetails($request, $category);

            if (!$category_details) {
                return ResponseHelper::error(
                    $category_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new CategoryResource($category_details),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
                ],
                201);

        } catch (Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function destroy(Category $category)
    {
        try{
            $category_details = $this->categoryService->deleteCategory($category);

            if (!$category_details) {
                return ResponseHelper::error(
                    $category_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }
            return  ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted', [], 'en'),
                    'ar' => trans('validation.data_deleted', [], 'ar'),
                ],
                200);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }

    }
}
