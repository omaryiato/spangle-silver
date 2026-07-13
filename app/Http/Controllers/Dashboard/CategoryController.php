<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Dashboard\CategoryService;
use App\Http\Resources\CategoryResource;
use App\Helpers\ResponseHelper;
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
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function show(Category $category)
    {
        $category_details = $this->categoryService->getCategoryDetails($category->id);

        return ResponseHelper::success(
            new CategoryResource($category_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function store(Request $request)
    {
        try{
            $category_details = $this->categoryService->addNewCategory($request);
            return ResponseHelper::success(
                new CategoryResource($category_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);

        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }
    public function update(Request $request, Category $category)
    {
        try{
            $category_details = $this->categoryService->updateCategoryDetails($request, $category->id);

            if (!$category_details) {
                return ResponseHelper::error(
                    $category_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new CategoryResource($category_details),
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);

        } catch (Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function destroy(Category $category)
    {
        try{
            $category_details = $this->categoryService->deleteCategory($category->id);

            if (!$category_details) {
                return ResponseHelper::error(
                    $category_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }
            return  ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted'),
                    'ar' => trans('validation.data_deleted'),
                ],
                200);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }

    }
}
