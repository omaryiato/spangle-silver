<?php

namespace App\Services\Dashboard;

use App\Models\CartProduct;
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
    public function getCartProductDetails(CartProduct $cartProduct)
    {
        return $this->cartProductRepository->getCartProductDetails($cartProduct);
    }
}

