<?php

namespace App\Services\Dashboard;

use App\Models\Category;
use App\Repositories\Dashboard\CategoryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

class CategoryService
{

    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryList(){
        return $this->categoryRepository->getCategoryList();
    }

    public function getCategoryDetails(Category $category){
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

        return $this->categoryRepository->addNewCategory(
            $this->prepareRequestInfo($category_request)
        );
    }

    public function updateCategoryDetails(object $request, Category $category)
    {
        $category_request = $request->validated();

        // $category_details = $this->categoryRepository->getCategoryDetails($id);

        // if(!$category_details){
        //     return null;
        // }


        if ($request->hasFile('category_image')) {

            // Delete old image
            if ($category->category_image) {

                $old_path = public_path($category->category_image);

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
            $category_request['category_image'] = $category->category_image;
        }


        return $this->categoryRepository->updateCategoryDetails(
            $category,
            $this->prepareRequestInfo($category_request)
        );
    }

    public function deleteCategory(Category $category){
        // $category_details = $this->categoryRepository->getCategoryDetails($category);
        // if(!$category_details){
        //     return null;
        // }
        return $this->categoryRepository->deleteCategory($category);
    }

    public function uploadCategoryImage(UploadedFile $file, string $category_en_name)
    {
        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ', '_', $category_en_name) . '.' . $extension;

        $folder_path = public_path("documents/categories");


        if (!File::exists($folder_path)) {
            File::makeDirectory($folder_path, 0755, true);
        }

        $webp_name = pathinfo($file_name, PATHINFO_FILENAME) . '.webp';

        $image = Image::decode($file);

        // encode to webp
        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: 85
        );

        // save encoded image
        $encoded->save("{$folder_path}/{$webp_name}");


        // $file->move($folder_path, $file_name);


        return "documents/categories/{$webp_name}";
    }

    public function prepareRequestInfo(array $request_info)
    {

        $request_data = [
            'category_en_name' => $request_info['category_en_name'] ?? null,
            'category_ar_name' => $request_info['category_ar_name'] ?? null,
            'category_description' => $request_info['category_description'] ?? null,
            'category_image' => $request_info['category_image'] ?? null,
            'status' => $request_info['status'] ?? 1,
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
