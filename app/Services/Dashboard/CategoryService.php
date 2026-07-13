<?php

namespace App\Service\Dashboard;

use App\Repositories\Dashboard\CategoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class CategoryService {

    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryList(){
        return $this->categoryRepository->getCategoryList();
    }

    public function getCategoryDetails(int $id){
        return $this->categoryRepository->getCategoryDetails($id);
    }

    public function addNewCategory($request)
    {
        $category_request = $request->all();

        if ($request->hasFile('category_image')) {
            $category_image = $this->uploadCategoryImage(
                $request->file('category_image'),
                $category_request['category_en_name']
            );

            $category_request['category_image'] = $category_image;
        }

        return $this->categoryRepository->addNewCategory(
            $this->prepareRequestInfo($category_request)
        );
    }

    public function updateCategoryDetails($request, int $id)
    {
        $category_request = $request->all();

        $category_details = $this->categoryRepository->getCategoryDetails($id);

        if(!$category_details){
            return null;
        }


        if ($request->hasFile('category_image')) {

            // Delete old image
            if ($category_details->category_image) {

                $old_path = public_path($category_details->category_image);

                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }


            // Upload new image
            $category_request['category_image'] = $this->uploadCategoryImage(
                $request->file('category_image'),
                $category_request['category_en_name']
            );

        } else {
            // keep old image
            $category_request['category_image'] = $category_details->category_image;
        }


        return $this->categoryRepository->updateCategoryDetails(
            $category_details,
            $this->prepareRequestInfo($category_request)
        );
    }

    public function deleteCategory(int $id){
        $category_details = $this->categoryRepository->getCategoryDetails($id);
        if(!$category_details){
            return null;
        }
        return $this->categoryRepository->deleteCategory($category_details);
    }

    public function uploadCategoryImage($file, string $category_en_name)
    {
        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ', '_', $category_en_name) . '.' . $extension;

        $folder_path = public_path("documents/categories");


        if (!File::exists($folder_path)) {
            File::makeDirectory($folder_path, 0755, true);
        }


        $file->move($folder_path, $file_name);


        return "documents/categories/{$file_name}";
    }

    public function prepareRequestInfo(array $request_info)
    {
        $request_data = [
            'category_en_name' => $request_info['category_en_name'] ?? null,
            'category_ar_name' => $request_info['category_ar_name'] ?? null,
            'category_description' => $request_info['category_description'] ?? null,
            'category_image' => $request_info['category_image'] ?? null,
            'status' => $request_info['status'] ?? null,
        ];


        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }


        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }


        return $request_data;
    }

}
