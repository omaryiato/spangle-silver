<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\CartProductRepository;

class CartProductService
{

    protected $cartProductRepository;

    public function __construct(CartProductRepository $cartProductRepository)
    {
        $this->cartProductRepository = $cartProductRepository;
    }

    // Funtion To Get Cart Product List
    public function getCartProductsList()
    {
        return  $this->cartProductRepository->getCartProductsList();
    }

    // getCartProductDetails Funtion To Get Cart Product Details
    public function getCartProductDetails(int $id)
    {
        return $this->cartProductRepository->getCartProductDetails($id);
    }
}

