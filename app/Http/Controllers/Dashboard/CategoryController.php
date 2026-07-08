<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Dashboard\CategoryService;
use App\Http\Resources\CategoryResource;
use App\Helpers\ResponseHelper;
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
        return ResponseHelper::success(CategoryResource::collection($category_list), "Category list returned Successfully.", 200);
    }

    public function show(int $id)
    {
        $category_details = $this->categoryService->getCategoryDetails($id);
        return ResponseHelper::success(new CategoryResource($category_details), "Catgeroy Details returned successfully.", 200);
    }

    public function store(Request $request)
    {
        try{
            $category_details = $this->categoryService->addNewCategory($request->all());
            return ResponseHelper::success(new CategoryResource($category_details), "Category Added Successfully.", 201);

        } catch(Exception $exeption){
            return ResponseHelper::error($exeption, "There's somthing wrong.", 400);
        }
    }
    public function update(Request $request, int $id)
    {
        try{
            $category_details = $this->categoryService->updateCategoryDetails($request->all(), $id);
            return ResponseHelper::success( new CategoryResource($category_details), "Category Updated Successfully.", 201);

        } catch (Exception $exception){
            return ResponseHelper::error($exception, "There's somthing wrong.", 400);
        }
    }

    public function destroy(int$id)
    {
        try{
            $this->categoryService->deleteCategory($id);
            return  ResponseHelper::success(null, "Category Deleted Successfully.", 200);
        } catch(Exception $exception){
            return ResponseHelper::error($exception, "There's somthing wrong.", 400);
        }

    }
}
