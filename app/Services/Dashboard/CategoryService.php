<?php

namespace App\Services\Dashboard;

use App\Models\Category;
use App\Repositories\Dashboard\CategoryRepository;
use App\Services\ActingUserService;
use App\Services\MediaStorageService;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, protected ActingUserService $actingUserService, protected MediaStorageService $mediaStorage)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryList()
    {
        return $this->categoryRepository->getCategoryList();
    }

    public function getCategoryDetails(Category $category)
    {
        return $this->categoryRepository->getCategoryDetails($category);
    }

    public function addNewCategory($request)
    {
        $category_request = $request->validated();

        if ($request->hasFile('category_image')) {
            $category_image = $this->uploadCategoryImage(
                $request->file('category_image'),
                $category_request['category_en_name']
            );

            $category_request['category_image'] = $category_image;
        }

        try {
            return $this->categoryRepository->addNewCategory($this->prepareRequestInfo($category_request));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($category_request['category_image'] ?? null);
            throw $exception;
        }
    }

    public function updateCategoryDetails(object $request, Category $category)
    {
        $data = $request->validated();
        unset($data['category_image']);
        $oldPath = $category->category_image;
        $newPath = null;
        if ($request->hasFile('category_image')) {
            $newPath = $this->uploadCategoryImage($request->file('category_image'), $category->category_en_name);
            $data['category_image'] = $newPath;
        }
        try {
            $result = $this->categoryRepository->updateCategoryDetails($category, $this->prepareRequestInfo($data, true));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($newPath);
            throw $exception;
        }
        if ($newPath) {
            $this->mediaStorage->delete($oldPath);
        }

        return $result;
    }

    public function deleteCategory(Category $category)
    {
        // $category_details = $this->categoryRepository->getCategoryDetails($category);
        // if(!$category_details){
        //     return null;
        // }
        return $this->categoryRepository->deleteCategory($category);
    }

    public function uploadCategoryImage(UploadedFile $file, string $category_en_name): string
    {
        return $this->mediaStorage->store($file, 'categories', true);
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {

        $request_data = [
            'category_en_name' => $request_info['category_en_name'] ?? null,
            'category_ar_name' => $request_info['category_ar_name'] ?? null,
            'category_description' => $request_info['category_description'] ?? null,
            'category_image' => $request_info['category_image'] ?? null,
            'status' => $request_info['status'] ?? 1,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }
}
