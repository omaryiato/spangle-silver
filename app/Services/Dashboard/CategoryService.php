<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\CategoryRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class CategoryService
{

    protected $categoryRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    CategoryRepository $categoryRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getCategoryList Funtion To Get Category List
    public function getCategoryList()
    {
        try {
            return  $this->categoryRepository->getCategoryList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getCategoryDetails Funtion To Get Category Details
    public function getCategoryDetails($category_id)
    {

        try {

            $category_details =  $this->categoryRepository->getCategoryDetails($category_id);

            return $category_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewCategory Funtion To Add new Category
    public function addNewCategory($category_details)
    {

        try {

            $category_id = $this->categoryRepository->addNewCategory($category_details);
            $get_category_details = $this->categoryRepository->getCategoryDetails($category_id);

            return $get_category_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateCategory Funtion To Update Category info
    public function updateCategory($category_details)
    {

        try {
            $this->categoryRepository->updateCategory($category_details);
            $get_category_details = $this->categoryRepository->getCategoryDetails($category_details['category_id']);

            return $get_category_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteCategory Funtion To Delete Category
    public function deleteCategory($category_id, $login_user)
    {
        try {

            return $this->categoryRepository->deleteCategory($category_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

