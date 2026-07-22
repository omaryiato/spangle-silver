<?php

namespace App\Repositories\Dashboard;


use App\Models\Category;


class CategoryRepository {

    public function getCategoryList()
    {
        return Category::with([
            'products',
            'products.images',
            'products.variants',
            'products.variants.color',
            'products.variants.size',
            'products.reviews',
            'products.reviews.user',
            'products.material',
            'products.stone'
        ])->get();
    }

    public function getCategoryDetails(Category $category){
        return $category->load([
            'products',
            'products.images',
            'products.variants',
            'products.variants.color',
            'products.variants.size',
            'products.reviews',
            'products.reviews.user',
            'products.material',
            'products.stone'
        ]);
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
