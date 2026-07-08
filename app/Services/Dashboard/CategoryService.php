<?php

namespace App\Service\Dashboard;

use App\Repositories\Dashboard\CategoryRepository;
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

    public function addNewCategory(array $category_request)
    {
        $category_image = $category_request["category_image"];

        $category_image_file_extension = $category_request['category_image']?->getClientOriginalExtension();
        $category_en_name = isset($category_request['category_image']) ?
                                    str_replace(' ', '_', $category_request['category_en_name']) : null;

        $category_request["category_image"] = "{$category_en_name}.{$category_image_file_extension}";

        $category_details = $this->categoryRepository->addNewCategory($category_request);

        if($category_image){
            $category_image_file_name = "{$category_en_name}.{$category_image_file_extension}";

            $category_image_folder_path = public_path("documents/category_".$category_request['category_id']);

            if (!File::exists($category_image_folder_path)) {
                File::makeDirectory($category_image_folder_path, 0755, true);
            }

            $category_image->move($category_image_folder_path, $category_image_file_name);
        }

        return $category_details;
    }

    public function updateCategoryDetails(array $category_request, int $id){
        $categroy_details = $this->categoryRepository->getCategoryDetails($id);

        if ($category_request['category_image']) {
            $category_image = $category_request["category_image"];

            $category_image_file_extension = $category_request['category_image']?->getClientOriginalExtension();
            $category_en_name = isset($category_request['category_image']) ?
                                        str_replace(' ', '_', $category_request['category_en_name']) : null;

            $category_request["category_image"] = "{$category_en_name}.{$category_image_file_extension}";
            $category_image_file_name = "{$category_en_name}.{$category_image_file_extension}";

            $category_image_folder_path = public_path("documents/category_image");

            if (!File::exists($category_image_folder_path)) {
                File::makeDirectory($category_image_folder_path, 0755, true);
            }

            $category_image->move($category_image_folder_path, $category_image_file_name);
        } else {
            $category_request['category_image'] = $categroy_details['category_image'];
        }

        return $this->categoryRepository->updateCategoryDetails($categroy_details, $category_request);
    }

    public function deleteCategory(int $id){
        $category_details = $this->categoryRepository->getCategoryDetails($id);
        return $this->categoryRepository->deleteCategory($category_details);
    }
}
