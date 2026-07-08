<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\CartProduct;


class CartProductRepository
{

    // getCartProductsList Funtion To Get CartProducts List
    public function getCartProductsList()
    {
        return CartProduct::with(['user','variant'])->get();
    }

    // getCartProductDetails Funtion To Get CartProduct Details
    public function getCartProductDetails(int $id)
    {
        return CartProduct::with(['user','variant'])->findorfail($id);
    }

    // addNewCartProduct Funtion To Add new CartProduct
    public function addNewCartProduct(array $cart_product_request)
    {
        return CartProduct::create($cart_product_request);
    }

    // updateCartProduct Funtion To Update CartProduct info
    public function updateCartProduct(CartProduct $cartProduct, array $cart_product_request)
    {
        $cartProduct->update($cart_product_request);
        return $cartProduct;
    }

    // deleteCartProduct Funtion To Delete CartProduct
    public function deleteCartProduct(CartProduct $cartProduct)
    {
        $cartProduct->delete();
        return $cartProduct;
    }
}

