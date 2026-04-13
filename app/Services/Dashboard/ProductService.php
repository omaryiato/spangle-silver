<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ProductRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class ProductService
{

    protected $productRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    ProductRepository $productRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->productRepository = $productRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getProductsList Funtion To Get Product List
    public function getProductsList($category_id)
    {
        try {

            return  $this->productRepository->getProductsList($category_id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getProductDetails Funtion To Get Product Details
    public function getProductDetails($product_id)
    {

        try {

            $product_details =  $this->productRepository->getProductDetails($product_id);

            return $product_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewProduct Funtion To Add new Product
    public function addNewProduct($product_details)
    {

        try {

            $product_id = $this->productRepository->addNewProduct($product_details);
            $get_product_details = $this->productRepository->getProductDetails($product_id);

            return $get_product_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateProduct Funtion To Update Product info
    public function updateProduct($product_details)
    {

        try {
            $this->productRepository->updateProduct($product_details);
            $get_product_details = $this->productRepository->getProductDetails($product_details['product_id']);

            return $get_product_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteProduct Funtion To Delete Product
    public function deleteProduct($product_id, $login_user)
    {
        try {

            return $this->productRepository->deleteProduct($product_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

