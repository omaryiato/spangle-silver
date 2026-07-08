<?php

namespace App\Repositories\Dashboard;


use App\Models\Category;


class CategoryRepository {

    public function getCategoryList()
    {
        return Category::all();
    }

    public function getCategoryDetails(int $id){
        return Category::findorfail($id);
    }

    public function addNewCategory(array $category_request){
        return Category::create($category_request);
    }

    public function updateCategoryDetails(Category $category, array $categroy_request){
        $category->update($categroy_request);
        return $category;
    }

    public function deleteCategory(Category $category){
        $category->delete();
        return $category;
    }

}
