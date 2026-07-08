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

    // addNewCartProduct Funtion To Add new Cart Product
    public function addNewCartProduct(array $cart_product_request)
    {
        return $this->cartProductRepository->addNewCartProduct($cart_product_request);
    }

    // updateCartProduct Funtion To Update Cart Product info
    public function updateCartProduct(array $cart_product_request, int $id)
    {
        $cart_product_details = $this->cartProductRepository->getCartProductDetails($id);
        return $this->cartProductRepository->updateCartProduct($cart_product_details, $cart_product_request);
    }

    // deleteCartProduct Funtion To Delete Cart Product
    public function deleteCartProduct($cart_product_request, int $id)
    {
        try {
            $cart_product_details = $this->cartProductRepository->getCartProductDetails($id);
            return $this->cartProductRepository->deleteCartProduct($cart_product_details);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

